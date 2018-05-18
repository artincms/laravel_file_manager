<?php

namespace ArtinCMS\LFM\Controllers;

use Illuminate\Http\Request;
use ArtinCMS\LFM\Models\File;

class SessionController extends ManagerController
{
    private function checkSectionOptions($section, $options, $items)
    {
        $selected_items = $this->getSelectedSectionItems($section);
        if ($selected_items)
        {
            $total = count($items) + count($selected_items);
        }
        elseif ($items)
        {
            $total = count($items);
        }
        else
        {
            $result['success'] = false;
            $result['error'] = 'Dont select items';
            return $result;
        }
        if ($total > $options['max_file_number'])
        {
            $result['success'] = false;
            $result['error'] = 'your cant insert more than' . $options['max_file_number'];
            return $result;
        }
        else
        {
            $mimeType = LFM_CheckMimeType($options['true_mime_type'], $items);
            if (!$mimeType['success'])
            {
                $result['success'] = false;
                $result['error'] = $mimeType['error'];
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
        $data = [];
        $section = $this->getSession($request->section);
        if (isset($section['selected']))
        {
            foreach ($request->items as $item)
            {
                $status = LFM_FindSessionSelectedId($section['selected'], $item['id']);
                if (!$status)
                {
                    $data[] = $this->createData($request, $item);
                }
            }
        }
        else
        {
            foreach ($request->items as $item)
            {
                $data[] = $this->createData($request, $item);
            }
        }
        return $data;
    }

    private function createData($request, $item)
    {
        $full_url = route('LFM.DownloadFile', ['type' => 'ID', 'id' => $item['id'], 'size_type' => $item['type'], 'default_img' => '404.png'
            , 'quality' => $item['quality'], 'width' => $item['width'], 'height' => $item['height']
        ]);
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
        $url = str_replace($protocol, '', $full_url);
        $url = str_replace('://', '', $url);
        $url = str_replace($_SERVER['HTTP_HOST'], '', $url);
        $file = File::find($item['id']);
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
        $data['message'] = "File with ID :" . $item['id'] . ' Inserted';
        return $data;
    }

    private function setSelectedFileToSession($request, $section, $data)
    {
        if ($request->has('section'))
        {
            if (session()->has('LFM'))
            {
                $LFM = session()->get('LFM');
                if (isset($LFM[$request->section]))
                {
                    $result['success'] = true;
                    $LFM[$section]['selected'] = array_merge($LFM[$section]['selected'], $data);
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
        else
        {
            $result['success'] = false;
        }
        return $result;
    }

    /*private function mergeToSelected($selected, $data)
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
    }*/

    private function getSelectedSectionItems($section)
    {
        $LFM = session('LFM');
        if ($LFM[$section])
        {
            if ($LFM[$section]['selected'])
            {
                return $LFM[$section]['selected'];
            }
        }
        return false;
    }

    private function listInsertedView($data, $section = false)
    {
        return view('laravel_file_manager::selected.list_inserted_view', compact('data', 'section'))->render();
    }

    private function gridInsertedView($data, $section = false)
    {
        return view('laravel_file_manager::selected.grid_inserted_view', compact('data', 'section'))->render();
    }

    private function smallInsertedView($data, $section = false)
    {
        return view('laravel_file_manager::selected.small_inserted_view', compact('data', 'section'))->render();
    }

    private function mediumInsertedView($data, $section = false)
    {
        return view('laravel_file_manager::selected.medium_inserted_view', compact('data', 'section'))->render();
    }

    private function largeInsertedView($data, $section = false)
    {
        return view('laravel_file_manager::selected.large_inserted_view', compact('data', 'section'))->render();
    }

    private function inlineJSInsertedView($data, $section = false)
    {
        return view('laravel_file_manager::selected.helpers.inline_js', compact('data', 'section'))->render();
    }

    private function inlineStyleInsertedView($data, $section = false)
    {
        return view('laravel_file_manager::selected.helpers.inline_style', compact('data', 'section'))->render();
    }

    public function createInsertData(Request $request)
    {
        $options = $this->getSectionOptions($request->section);
        if ($options['success'])
        {
            $check_options = $this->checkSectionOptions($request->section, $options['options'], $request->items);
            if ($check_options['success'])
            {
                $data = $this->createAllInsertData($request);
                $section = $request->section;
                $view['list'] = $this->listInsertedView($data, $section);
                $view['grid'] = $this->gridInsertedView($data, $section);
                $view['small'] = $this->smallInsertedView($data, $section);
                $view['medium'] = $this->mediumInsertedView($data, $section);
                $view['large'] = $this->largeInsertedView($data, $section);
                $view['inline_js'] = $this->inlineJSInsertedView($data, $section);
                $view['inline_style'] = $this->inlineStyleInsertedView($data, $section);
                $result_session = $this->setSelectedFileToSession($request, $section, $data);
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
            $datas = $this->createAllInsertData($request);
        }
        $result['data'] = $datas;
        return response()->json($result);
    }

    public function getSession($section)
    {
        return LFM_GetSection($section);
    }

    public function deleteSessionInsertItem(Request $request)
    {
        if ($request->ajax())
        {
            $section = $request->section;
            $file_id = $request->file_id;
        }
        $LFM = session()->get('LFM');
        if ($LFM[$section])
        {
            $selected = $LFM[$section]['selected'];
            foreach ($selected as $key => $value)
            {
                if ($file_id == $value['file']['id'])
                {
                    unset($selected[$key]);
                }
            }
            $LFM[$section]['selected'] = $selected;
            session()->put('LFM', $LFM);
            $result['success'] = true;
        }
        else
        {
            $result['success'] = false;
        }
        return response()->json($result);
    }
}
