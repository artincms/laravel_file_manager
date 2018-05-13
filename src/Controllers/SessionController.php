<?php

namespace ArtinCMS\LFM\Controllers;

use ArtinCMS\LFM\Models\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SessionController extends ManagerController
{
    public function createInsertData(Request $request)
    {
        $options = $this->getSectionOptions($request->section);
        if ($options['success'])
        {
            $check_options = $this->checkSectionOptions($request->section, $options['options'], $request->items);
            if ($check_options['success'])
            {
                $datas = $this->createAllInsertData($request);
                $view['grid'] = $this->gridInsertedView($datas, $request->section);
                $view['small'] = $this->smallInsertedView($datas, $request->section);
                $view['thumb'] = $this->mediumInsertedView($datas, $request->section);
                $view['large'] = $this->largeInsertedView($datas, $request->section);
                $view['list'] = $this->listInsertedView($datas, $request->section);
                $result_session = $this->setSelectedFileToSession($request, $request->section, $datas);
                $result['view'] = $view;
            }
            else
            {
                $datas['success'] = false;
                $datas['error'] = $check_options['error'];
            }
        }
        else
        {
            $datas['success'] = false;
            $datas['error'] = $options['error'];
        }
        $result['data'] = $datas;
        return response()->json($result);
    }

    private function checkSectionOptions($name, $options, $items)
    {
        $selected_items = $this->getSelectedSectionItems($name);
        if ($selected_items)
        {
            $totall = count($items) + count($selected_items);
        }
        elseif ($items)
        {
            $totall = count($items);
        }
        else
        {
            $result['success'] = false;
            $result['error'] = 'Dont select items';
            return $result;
        }
        if ($totall > $options['max_file_number'])
        {
            $result['success'] = false;
            $result['error'] = 'your cant insert more than' . $options['max_file_number'];
            return $result;
        }
        else
        {
            $mimetype = LFM_CheckMimeType($options['true_mime_type'], $items);
            if (!$mimetype['success'])
            {
                $result['success'] = false;
                $result['error'] = $mimetype['error'];
                return $result;
            }
            else
            {
                $result['success'] = true;
            }
        }
        return $result;
    }

    private function createAllInsertData($request)
    {
        $datas = [];
        $section = $this->getSession($request->section);
        if (isset($section['selected']))
        {
            foreach ($request->items as $item)
            {
                $id = $item['id'];
                $status = LFM_FindSessionSelectedId($section['selected'], $id);
                if (!$status)
                {
                    $full_url = route('LFM.DownloadFile', ['type' => 'ID', 'id' => $id, 'size_type' => $item['type'], 'default_img' => '404.png'
                        , 'quality' => $item['quality'], 'width' => $item['width'], 'height' => $item['height']
                    ]);
                    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
                    $url = str_replace($protocol, '', $full_url);
                    $url = str_replace('://', '', $url);
                    $url = str_replace($_SERVER['HTTP_HOST'], '', $url);
                    $file = File::find($id);
                    $image_type = config('laravel_file_manager.allowed_pic');
                    if (in_array($file->mimeType, $image_type))
                    {
                        $icon = 'image';
                    }
                    else
                    {
                        $icon = $file->FileMimeType->icon_class;
                    }

                    if (!$file->user)
                    {
                        $user = 'public';
                    }
                    else
                    {
                        $user = $file->user->name;
                    }
                    switch ($request->type)
                    {
                        case "orginal":
                            $file_title_disc = $file->filename;
                            $version = $file->versioin;
                            break;
                        case "large":
                            $file_title_disc = $file->large_filename;
                            $version = $file->large_version;
                            break;
                        case "medium":
                            $file_title_disc = $file->medium_filename;
                            $version = $file->medium_version;
                            break;
                        case "small":
                            $file_title_disc = $file->small_filename;
                            $version = $file->small_version;
                            break;
                        default:
                            $file_title_disc = $file->filename;
                            $version = $file->versioin;
                            break;
                    }
                    $data['full_url'] = $full_url;
                    $data['url'] = $url;
                    $data['file'] = [
                        'id' => $file->id,
                        'name' => $file->originalName,
                        'type' => $item['type'],
                        'width' => $item['width'],
                        'height' => $item['height'],
                        'quality' => $item['quality'],
                        'title_file_disc' => $file_title_disc,
                        'created' => $file->created_at,
                        'updated' => $file->updated_at,
                        'user' => $user,
                        'icon' => $icon,
                        'size' => $file->size,
                        'version' => $version
                    ];
                    $data['success'] = true;
                    $data['message'] = "File with ID :" . $id . ' Inserted';
                    $datas[] = $data;
                }
            }
        }
        return $datas;
    }

    private function setSelectedFileToSession($request, $section, $datas)
    {
        if ($request->has('section'))
        {
            if (session()->has('LFM'))
            {
                $LFM = session()->get('LFM');
                if (isset($LFM[$request->section]))
                {
                    $result['success'] = true;
                    $LFM[$section]['selected'] = array_merge($LFM[$section]['selected'], $datas);
                    session()->put('LFM', $LFM);
                    return $result;
                }
                else
                {
                    $result['success'] = false;
                }
            }
            else
            {
                $result['success'] = false;
            }
        }
        return $result;
    }

    public function gridInsertedView($datas, $section = false)
    {
        $view = view('laravel_file_manager::selected.grid_inserted_view', compact('datas', 'section'))->render();
        return $view;
    }

    public function smallInsertedView($datas, $section = false)
    {
        $view = view('laravel_file_manager::selected.small_inserted_view', compact('datas', 'section'))->render();
        return $view;
    }

    public function mediumInsertedView($datas, $section = false)
    {
        $view = view('laravel_file_manager::selected.medium_inserted_view', compact('datas', 'section'))->render();
        return $view;
    }

    public function largeInsertedView($datas, $section = false)
    {
        $view = view('laravel_file_manager::selected.large_inserted_view', compact('datas', 'section'))->render();
        return $view;
    }

    public function listInsertedView($datas, $section = false)
    {
        $view = view('laravel_file_manager::selected.list_inserted_view', compact('datas', 'section'))->render();
        return $view;
    }

    private function mergeToSelected($selected, $data)
    {
        $status = LFM_FindSessionSelectedId($selected, $data['file']['id']);
        if ($status == false)
        {
            $result ['data'] = $data;
        }
        else
        {
            $result['error'] = 'The File ID ' . $data['file']['id'] . ' is repeated';
        }
        return $result;
    }

    public function getSession($section)
    {
        return LFM_GetSection($section);
    }

    public function deleteSessionInsertItem($name, $id)
    {
        $LFM = session()->get('LFM');
        if ($LFM[$name])
        {
            $selected = $LFM[$name]['selected'];
            foreach ($selected as $key => $value)
            {
                if ($id == $value['file']['id'])
                {
                    unset($selected[$key]);
                }
            }
            $LFM[$name]['selected'] = $selected;
            session()->put('LFM', $LFM);
            return $LFM;
        }
        else
        {
            return false;
        }
    }

    public function deleteSelectedPostId(Request $request)
    {
        $selected = $this->getSelectedSectionItems($request->name);
        foreach ($selected as $key => $value)
        {
            if ($request->id == $value['file']['id'])
            {
                unset($selected[$key]);
            }
        }
        $LFM[$request->name]['selected'] = $selected;
        session()->put('LFM', $LFM);
        return $LFM;
    }

    private function getSelectedSectionItems($name)
    {
        $LFM = session('LFM');
        if ($LFM[$name])
        {
            if ($LFM[$name]['selected'])
            {
                return $LFM[$name]['selected'];
            }
        }
        return false;
    }
}
