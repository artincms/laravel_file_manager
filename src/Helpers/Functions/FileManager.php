<?php
/* Hamahang File Manager :: LFM*/

use ArtinCMS\LFM\Models\FileMimeType;
use ArtinCMS\LFM\Traits\lfmFillable ;
use ArtinCMS\LFM\Models\File;
function LFM_SetSessionOption($name, $option)
{
    $mime = [];
    foreach ($option['true_file_extension'] as $ext)
    {
        $MimeType = FileMimeType::where('ext', '=', $ext)->first();
        if ($MimeType)
        {
            $mime[] = $MimeType->mimeType;
        }
    }
    $option['true_mime_type'] = $mime;
    $LFM[$name]['options'] = $option;
    $LFM[$name]['selected'] = [];
    session()->put('LFM', $LFM);
    return $LFM;
}

function LFM_CheckMimeType($mimetype, $items)
{
    foreach ($items as $item)
    {
        $file = \ArtinCMS\LFM\Models\File::find($item['id']);
        if (!in_array($file->mimeType, $mimetype))
        {
            $result['success'] = false;
            $result['error'] = 'File ' . $file->originalName . ' Not true mime type';
            $result['item_error'] = $item;
            return $result;
        }
        else
        {
            $result['success'] = true;
        }

    }
    return $result;
}

function LFM_FindSessionSelectedId($selected, $id)
{
    foreach ($selected as $select)
    {
        if ($select['file']['id'] == $id)
        {
            return true;
        }
    }
    return false;
}

function LFM_CheckFalseString($input, $replace_input = "false")
{
    if ($input)
    {
        return $input;
    }
    else
    {
        return $replace_input;
    }
}

function LFM_CreateModalFileManager($section, $options = false, $insert = false, $callback = false, $modal_id = 'FileManager', $header = 'File manager', $button_id = 'show_modal', $button_content = 'input file')
{
    $session_option = LFM_SetSessionOption($section, $options);
    //create html content and button
    $src = route('LFM.ShowCategories', ['section' => $section, 'insert' => $insert, 'callback' => $callback]);
    $result['content'] = view("laravel_file_manager::file_manager", compact("src", "modal_id", 'header', 'button_content', 'section','callback'))->render();
    $result['button'] = '<button class="btn btn-default" href="" data-toggle="modal" data-target="#' . $modal_id . '" id="' . $button_id . '">' . $button_content . '</button>';
    return $result;
}

function LFM_GetSection($section)
{
    $LFM = session()->get('LFM');
    if (isset($LFM[$section]))
    {
        return $LFM[$section];
    }
    else
    {
        return false;
    }
}

function LFM_GetSectionFile($section)
{
    $sec = LFM_GetSection($section);
    if ($sec && isset($sec['selected']) && count($sec['selected']) >= 1)
    {
        return $sec['selected'];
    }
    else
    {
        return false;
    }
}

function LFM_SaveSingleFile($obj_model, $column_name, $section)
{
    $files = LFM_GetSectionFile($section);
    if ($files)
    {
        if (isset($files[0]) && isset($files[0]['id']))
        {
            $obj_model->$column_name = $files[0]['id'];//first select
            $obj_model->save();
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

function LFM_SaveMultiFile($obj_model, $section, $type =null, $relation_name = 'files', $attach_type = 'attach')
{
    if ($attach_type != 'attach')
    {
        $attach_type = 'sync' ;
    }
    $files = LFM_GetSectionFile($section);
    if ($files)
    {
        $arr_ids = [];
        foreach ($files as $file)
        {
            if (isset($file['file']['id']))
            {
                $arr_ids[$file['file']['id']] = ['type' =>$type];
            }
        }
        $res = $obj_model->$relation_name()->$attach_type($arr_ids) ;
        LFM_DestroySection($section) ;
        return $res ;
    }
    else
    {
        return false;
    }
}

function LFM_DestroySection($section)
{
    if (session()->has('LFM'))
    {
        $LFM = session()->get('LFM') ;
        if (isset($LFM[$section]))
        {
            unset($LFM[$section]) ;
            session()->put('LFM' , $LFM) ;
            return true ;
        }
        else
        {
            return false ;
        }
    }
    else
    {
        return false ;
    }
}



