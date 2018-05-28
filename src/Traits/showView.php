<?php
namespace ArtinCMS\LFM\Traits ;

trait ShowView {
    public function setInsertedView($section, $data)
    {
        $view['list'] = $this->listInsertedView($data, $section);
        $view['grid'] = $this->gridInsertedView($data, $section);
        $view['small'] = $this->smallInsertedView($data, $section);
        $view['medium'] = $this->mediumInsertedView($data, $section);
        $view['large'] = $this->largeInsertedView($data, $section);
        return $view;
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

    public function setSelectedFileToSession($request, $section, $data)
    {
        if ($request->has('section'))
        {
            if (session()->has('LFM'))
            {
                $LFM = session()->get('LFM');
                if (isset($LFM[$request->section]))
                {
                    $result['success'] = true;
                    $LFM[$section]['selected']['data'] = array_merge($LFM[$section]['selected']['data'], $data);
                    $LFM[$section]['selected']['view'] = $this->setInsertedView($request->section, $LFM[$section]['selected']['data']);
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

}
