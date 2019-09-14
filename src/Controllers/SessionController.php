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
            $result[ $section ]['success'] = false;
            $result[ $section ]['error'] = __('filemanager.dont_select_items');

            return $result;
        }
        if ($total > $options['max_file_number'])
        {
            $result[ $section ]['success'] = false;
            $result[ $section ]['error'] = __('filemanager.you_cant_inserted_more_than') . $options['max_file_number'] . __('filemanager.file_upload');

            return $result;
        }
        else
        {
            $mimeType = LFM_CheckMimeType($options['true_mime_type'], $items);
            if (!$mimeType['success'])
            {
                $result[ $section ]['success'] = false;
                $result[ $section ]['error'] = $mimeType['error'];

                return $result;
            }
            else
            {
                $result[ $section ]['success'] = true;
            }
        }

        return $result;
    }

    private function createAllInsertData($request)
    {
        $data = [];
        $section = LFM_GetSection($request->section);
        if (isset($section['selected']['data']))
        {
            foreach ($request->items as $item)
            {
                $status = LFM_FindSessionSelectedId($section['selected']['data'], LFM_GetDecodeId($item['id']));
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
        $full_url = lfm_secure_route(
            'LFM.DownloadFile',
            [
                'type'        => 'ID',
                'id'          => $item['id'],
                'size_type'   => $item['type'],
                'default_img' => '404.png',
                'quality'     => $item['quality'],
                'width'       => $item['width'],
                'height'      => $item['height']
            ]
        );

        $full_url_large = lfm_secure_route(
            'LFM.DownloadFile',
            [
                'type'        => 'ID',
                'id'          => $item['id'],
                'size_type'   => 'small',
                'default_img' => '404.png',
                'quality'     => $item['quality'],
                'width'       => 300,
                'height'      => 180
            ]
        );

        $full_url_medium = lfm_secure_route(
            'LFM.DownloadFile',
            [
                'type'        => 'ID',
                'id'          => $item['id'],
                'size_type'   => 'small',
                'default_img' => '404.png',
                'quality'     => $item['quality'],
                'width'       => 175,
                'height'      => 125
            ]
        );
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
        $url = str_replace($protocol, '', $full_url);
        $url = str_replace('://', '', $url);
        $url = str_replace($_SERVER['HTTP_HOST'], '', $url);
        $file = File::find(LFM_GetDecodeId($item['id']));
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
            $name_column = config('laravel_file_manager.user_name_column');
            $user = $file->user->$name_column;
        }
        switch ($request->type)
        {
            case "original":
                $version = $file->versioin;
                break;
            case "large":
                $version = $file->large_version;
                break;
            case "medium":
                $version = $file->medium_version;
                break;
            case "small":
                $version = $file->small_version;
                break;
            default:
                $version = $file->versioin;
                break;
        }
        $data['full_url'] = $full_url;
        $data['full_url_medium'] = $full_url_medium;
        $data['full_url_large'] = $full_url_large;
        $data['url'] = $url;
        $data['file'] = [
            'id'            => LFM_getEncodeId($file->id),
            'original_name' => $file->original_name,
            'type'          => $item['type'],
            'width'         => $item['width'],
            'height'        => $item['height'],
            'quality'       => $item['quality'],
            'created'       => $file->created_at,
            'updated'       => $file->updated_at,
            'user'          => $user,
            'icon'          => $icon,
            'size'          => $file->size,
            'version'       => $version
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
                if (isset($LFM[ $request->section ]))
                {
                    $result['success'] = true;
                    $LFM[ $section ]['selected']['data'] = array_merge($LFM[ $section ]['selected']['data'], $data);
                    session()->put('LFM', $LFM);
                    $LFM[ $section ]['selected']['view'] = LFM_SetInsertedView($request->section, $LFM[ $section ]['selected']['data']);
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


    private function getSelectedSectionItems($section)
    {
        $LFM = session('LFM');
        if ($LFM[ $section ])
        {
            if ($LFM[ $section ]['selected'])
            {
                return $LFM[ $section ]['selected']['data'];
            }
        }

        return false;
    }

    public function createInsertData(Request $request)
    {
        $options = $this->getSectionOptions($request->section);
        if ($options['success'])
        {
            $check_options = $this->checkSectionOptions($request->section, $options['options'], $request->items);
            if ($check_options[ $request->section ]['success'])
            {
                $data = $this->createAllInsertData($request);
            }
            else
            {
                $data[ $request->section ]['success'] = false;
                $data[ $request->section ]['error'] = $check_options[ $request->section ]['error'];

                return response()->json($data);
            }
        }
        else
        {
            $data = $this->createAllInsertData($request);
        }
        $result_session = $this->setSelectedFileToSession($request, $request->section, $data);
        if ($result_session['success'])
        {
            $session = LFM_GetSection($request->section);
            if ($session)
            {
                $result[ $request->section ]['success'] = true;
                if (!isset($options['options']['response_types']))
                {
                    $result[ $request->section ]['data'] = $session['selected']['data'];
                    $result[ $request->section ]['view'] = $session['selected']['view'];
                    $result[ $request->section ]['new_inserted_data'] = $data;
                }
                else
                {
                    $respnse_types = $options['options']['response_types'];
                    if ($respnse_types)
                    {
                        foreach ($respnse_types as $respnse_type)
                        {
                            switch ($respnse_type)
                            {
                                case "json":
                                    $result[ $request->section ]['new_inserted_data'] = $data;
                                    $result[ $request->section ]['data'] = $session['selected']['data'];
                                    break;
                                case "list_html":
                                    $result[ $request->section ]['view']['list'] = $session['selected']['view']['list'];
                                    break;
                                case "medium_html":
                                    $result[ $request->section ]['view']['medium'] = $session['selected']['view']['medium'];
                                    break;
                                case "large_html":
                                    $result[ $request->section ]['view']['large'] = $session['selected']['view']['large'];
                                    break;
                                case "grid_html":
                                    $result[ $request->section ]['view']['grid'] = $session['selected']['view']['grid'];
                                    break;
                                case "view":
                                    $result[ $request->section ]['view'] = $session['selected']['view'];
                                    break;
                            }
                        }
                    }
                }
                $result[ $request->section ]['available'] = LFM_CheckAllowInsert($request->section)['available'];
            }
        }
        else
        {
            $result['success'] = false;
        }

        return response()->json($result);
    }

    public function getSessionInsertedItems(Request $request)
    {
        return LFM_GetSection($request->section);
    }

    public function deleteSessionInsertItem(Request $request)
    {
        $section = $request->section;
        $file_id = $request->file_id;

        $LFM = session()->get('LFM');
        if ($LFM[ $section ])
        {
            $selected = $LFM[ $section ]['selected']['data'];
            if ($selected)
            {
                foreach ($selected as $key => $value)
                {
                    if ($file_id == $value['file']['id'])
                    {
                        unset($selected[ $key ]);
                    }
                }
                $LFM[ $section ]['selected']['data'] = $selected;
                session()->put('LFM', $LFM);
                $result[ $request->section ] = $LFM[ $section ]['selected'];
                $result[ $request->section ]['available'] = LFM_CheckAllowInsert($request->section)['available'];
                $result['success'] = true;
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

        return response()->json($result);
    }

    public function getSession($section)
    {
        $result = LFM_GetSection($section);

        return json_encode($result['selected']['data']);
    }
}
