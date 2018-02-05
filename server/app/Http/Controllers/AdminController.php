<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function getMediaList (Request $request) {
        $mediaList = Model\Media::select('id', 'name', 'logo_url')->get()->toArray();

        return $this->output(['dataList' => $mediaList]);
    }

    public function addMedia (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'name' => 'required|string',
            'logoUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Media::firstOrCreate([
            'name' => $name,
            'logo_url' => $logoUrl,
        ]);

        return $this->output();
    }

    public function updMedia (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'mediaId' => 'required|numeric',
            'name' => 'required|string',
            'logoUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Media::where('id', $mediaId)->update([
            'name' => $name,
            'logo_url' => $logoUrl,
        ]);

        return $this->output();
    }

    public function delMedia (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'mediaId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Media::where('id', $mediaId)->delete();

        return $this->output();
    }

    public function getSocialList (Request $request) {
        $socialList = Model\Social::select('id', 'font_class', 'name')->get()->toArray();

        return $this->output(['dataList' => $socialList]);
    }

    public function addSocial (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'name' => 'required|string',
            'fontClass' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Social::firstOrCreate([
            'name' => $name,
            'font_class' => $fontClass,
        ]);

        return $this->output();
    }

    public function updSocial (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'socialId' => 'required|numeric',
            'name' => 'required|string',
            'fontClass' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Social::where('id', $socialId)->update([
            'name' => $name,
            'font_class' => $fontClass,
        ]);

        return $this->output();
    }

    public function delSocial (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'socialId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Social::where('id', $socialId)->delete();

        return $this->output();
    }

}
