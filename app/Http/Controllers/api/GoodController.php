<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Good;
use App\Models\GoodCategory;
use App\Models\GoodDetail;
use App\Models\GoodOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GoodController extends Controller
{
    private function makeJson($state, $data = null, $msg = null)
    {
        return response()->json(['state' => $state, 'data' => $data, 'msg' => $msg])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function callGoodEditor(Good $good = null)
    {
        $categories = GoodCategory::Where('state', 1)->get();
        if (is_null($good)) {
            return view('good.goodEditor', compact('categories'));
        } else {
            return view('good.goodEditor', compact('categories', 'good'));
        }
    }

    public function goodCreate(Good $good = null, Request $request)
    {
        $update = false;
        $params = $request->only('name', 'caption', 'category', 'hot');
        // return $this->makeJson(0, $request->hot, null);

        if (is_null($good)) {
            // return $this->makeJson(0, $params, null);
            $result = Good::create($params);
            if ($result->id == '') {
                return $this->makeJson(0, $result, 'GOOD_CREATE_ERROR');
            }
            $good = $result;
        } else {
            $result = $good->update($params);
            if (!$result) {
                return $this->makeJson(0, $result, 'GOOD_UPDATE_ERROR');
            }
            $update = true;
        }

        $id = $good->id;
        $good = Good::Where('id', $id)->get()->first();
        $savePath = '/storage/goods/' . $id;
        $path = '/public/goods/' . $id;
        $galleryList = explode(',', $request->galleries);

        // return $this->makeJson(0, $update, null);

        if ($request->has('cover')) {
            if ($update) {
                $result = Storage::delete(str_replace('storage', 'public', $good->cover));
                if (!$result) {
                    return $this->makeJson(0, $result, 'OLD_COVER_DELETE_ERROR');
                }
            }
            $file = str_replace('data:image/png;base64,', '', $request->cover);
            $file = str_replace(' ', '+', $file);
            $file = base64_decode($file);
            $filename = uniqid('cover', false) . '.png';
            $result = Storage::put($savePath . '/' . $filename, $file);
            if (!$result) {
                return $this->makeJson(0, $result, 'GOOD_COVER_SAVE_ERROR');
            }
            $result = $good->update(['cover' => $path . '/' . $filename]);
            if (!$result) {
                return $this->makeJson(0, $result, 'GOOD_COVER_INSERT_ERROR');
            }
        }

        if ($request->has('galleries') && $request->galleries != "") {
            $gallery = array();
            $galleries = explode(',', $request->galleries);
            if ($update) {
                $delete = array();
                $old = unserialize(base64_decode($good->gallery));
                for ($i = 0; $i < count($old); $i++) {
                    for ($j = 0; $j < count($galleries); $j++) {
                        $newG = $path . '/' . $galleries[$j];
                        if ($old[$i] == $newG) {
                            array_push($gallery, $old[$i]);
                            break;
                        }
                        array_push($delete, $old[$i]);
                    }
                }
                foreach ($delete as $del) {
                    $result = Storage::delete(str_replace('storage', 'public', $del));
                    if (!$result) {
                        return $this->makeJson(0, $result, 'DELETE_OLD_IMAGE_ERROR');
                    }
                }
            }
            foreach ($galleries as $g) {
                $tg = str_replace('.', '_', $g);
                if ($request->hasFile($tg)) {
                    $file = $request->file($tg);
                    $filename = $g;
                    $result = Storage::putFileAs($savePath, $file, $filename);
                    if (!$result) {
                        return $this->makeJson(0, $result, 'GALLERY_SAVE_ERROR');
                    }
                    array_push($gallery, $path . '/' . $filename);
                }
            }
            $gallery = base64_encode(serialize($gallery));
            // return $this->makeJson(0, $gallery, null);
            $result = $good->update(['gallery' => $gallery]);
            if (!$result) {
                return $this->makeJson(0, $result, 'GALLERY_INSERT_ERROR');
            }
        }
        return $this->makeJson(1, $id, null);
    }

    public function goodDelete(Good $id)
    {
        $result = $id->delete();
        if (!$result) {
            return $this->makeJson(0, $result, 'GOOD_DELETE_ERROR');
        } else {
            return $this->makeJson(1, null, null);
        }
    }

    public function goodList($category = null)
    {
        $goods = Good::Where('state', 1);
        if (!is_null($category)) {
            $goods = $goods->Where('category', $category);
        }
        $goods = $goods->pagination(12);
        return view('good.list', compact('goods', 'category'));
    }

    public function goodShow(Good $good)
    {
        if (is_null($good)) {
            return view('good.unknown');
        } else {
            return view('good.show', compact('good'));
        }
    }

    public function callCategoryEditor(GoodCategory $id = null)
    {
        $categories = GoodCategory::Where('state', 1)->Where('sub', 0)->get();
        if (is_null($id)) {
            return view('good.categoryEditor', compact('categories'));
        } else {
            return view('good.categoryEditor', compact('id', 'categories'));
        }
    }

    public function categoryCreate(GoodCategory $cate = null, Request $request)
    {
        $params = $request->only('name', 'content', 'state');
        if (is_null($cate)) {
            $result = GoodCategory::create($params);
        } else {
            $result = $cate->update($params);
        }
        if (!$result) {
            if (is_null($cate)) {
                return $this->makeJson(0, $result, 'GOOD_CATEGORY_CREATE_ERROR');
            } else {
                return $this->makeJson(0, $result, 'GOOD_CATEGORY_UPDATE_ERROR');
            }
        } else {
            return $this->makeJson(1, null, null);
        }
    }

    public function categoryDelete(Request $request)
    {
        $data = $request->id;
        if (gettype($data) == 'string') {
            $result = GoodCategory::Where('id', $data)->get()->first()->delete();
            if (!$result) {
                return $this->makeJson(0, $result, 'CATEGORY_DELETE_ERROR');
            }
        } else if (gettype($data) == 'array') {
            foreach ($data as $d) {
                $result = GoodCategory::Where('id', $d)->get()->first()->delete();
                if (!$result) {
                    return $this->makeJson(0, $result, 'CATEGORY_DELETE_ERROR');
                }
            }
        }

        return $this->makeJson(1, null, null);

    }

    public function categoryList($id)
    {
        $goods = Good::Where('category', $id)->get();
        if ($goods->count() > 0) {
            return $this->makeJson(1, $goods, null);
        } else {
            return $this->makeJson(0, $goods, 'NO_DATA');
        }
    }

    public function orderListAdmin(Request $request)
    {
        $orders = $this->getOrderList('admin', $request);
        return view('good.listBackend', compact('orders'));
    }

    public function orderList(Request $request)
    {
        $orders = $this->getOrderList('user', $request);
        return view('good.list', compact('orders'));
    }

    private function getOrderList($type, $request)
    {
        $order = GoodOrder::Select('*');

        if ($type == 'user') {
            $user = Auth::id();
            $order->where('userId', $user);
        }

        if ($request->has('state')) {
            $order = $order->where('state', $request->get('state'));
        }

        if ($request->has('start') && $request->has('end')) {
            if ($request->start <= $request->end) {
                $order = $order->where('created_at', '>=', $request->start . '%')->where('created_at', '=<', $request->end . '%');
            } else {
                return $this->makeJson(0, null, 'START_BIG_THAN_END');
            }
        }

        if ($request->has('sort')) {
            if ($request->sort == 'ASC') {
                $order = $order->orderBy('created_at', 'asc');
            } else {
                $order = $order->orderBy('created_at', 'desc');
            }
        }

        if ($request->has('limit')) {
            $limit = $request->limit;
        } else {
            $limit = 15;
        }

        return $order->pagination($limit);

    }

    public function showOrderAdmin($serial)
    {
        $order = $this->getOrder('admin', $serial);
        return view('good.showBackend', compact('order'));
    }

    public function showOrder($serial)
    {
        $order = $this->getOrder('user', $serial);
        return view('good.show', compact('order'));

    }

    private function getOrder($type, $serial)
    {
        $order = GoodOrder::where('serial', $serial);
        if ($type == 'user') {
            $user = Auth::id();
            $order = $order->where('userId', $user);
        }
        return $order;
    }

    public function orderCreate($serial = null, Request $request)
    {
        $orderParams = $request->only('total', 'freight', 'remark', 'state');
        $orderParams['userId'] = Auth::id();
        $result = GoodOrder::create($orderParams);
        if ($result->id == '') {
            return $this->makeJson(0, $result, 'ORDER_CREATE_ERROR');
        }
        $id = $result->id;
        $good = GoodOrder::Where('id', $id)->get()->first();
        $serial = $good->serial;
        $createTime = substr($good->created_at, 0, 18);
        $details = array();
        $item = ['goodId', 'type', 'quantity', 'amount'];
        $tempArray = explode(',', $result->details);
        $count = 0;
        foreach ($tempArray as $t) {
            $details[$item[$count]] = $t;
            $count++;
            if ($count == 4) {
                $details['orderId'] = $id;
                $result = GoodDetail::create($details);
                if ($result->id == '') {
                    return $this->makeJson(0, $result, 'DETAIL_CREATE_ERROR');
                }
                $details = array();
                $count = 0;
            }
        }
        return view('good.success', compact('serial', 'createTime'));
    }

    public function changeOrder(GoodOrder $order, Request $request)
    {
        $params = $request->only('total', 'freight', 'remark', 'state');
        $result = $order->update($params);
        $id = $order->id;
        if (!$result) {
            return $this->makeJson(0, $result, 'ORDER_UPDATE_ERROR');
        }

        $changeTemp = $request->changeList;
        $changeList = array();
        $temp = array();
        $count = 0;
        foreach ($changeTemp as $ct) {
            array_push($temp, $ct);
            $count++;
            if ($count == 2) {
                array_push($changeList, $temp);
                $temp = array();
                $count = 0;
            }
        }

        foreach ($changeList as $c) {
            $detailId = $c[0];
            $params = $request[$c[0]];
            switch ($c['1']) {
                case 'add':
                    $params['orderId'] = $id;
                    $result = GoodDetail::create($params);
                    if ($result->id == '') {
                        return $this->makeJson(0, $result, 'DETAIL_CREATE_ERROR');
                    }
                    break;
                case 'change':
                    $result = GoodDetail::Where('id', $detailId)->update($params);
                    if (!$result) {
                        return $this->makeJson(0, $result, 'DETAIL_UPDATE_ERROR');
                    }
                    break;
                case 'delete':
                    $result = GoodDetail::Where('id', $detailId)->delete();
                    if (!$result) {
                        return $this->makeJson(0, $result, 'DETAIL_DELETE_ERROR');
                    }
                    break;
            }
        }
        return $this->makeJson(1, null, null);
    }

    public function orderVoid(GoodOrder $order, Request $request)
    {
        $result = $order->update(['state' => $request->state]);
        if (!$result) {
            return $this->makeJson(0, $result, 'ORDER_VOID_ERROR');
        } else {
            return $this->makeJson(1, null, null);
        }
    }
}