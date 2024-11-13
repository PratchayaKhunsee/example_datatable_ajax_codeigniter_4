<?php
namespace App\Controllers;

use App\Models\Item;

/**
 * ควบคุมการทำงานภายในพาธ "/items"
 */
class ItemsController extends BaseController
{
    public function index(): string
    {
        return view('items/index');
    }

    /**
     * GET - "/items/list"
     * @return void
     */
    public function list(): void
    {
        $uri = $this->request->getUri();
        $uri->getQuery();
        $query = array();
        parse_str($uri->getQuery(), $query);
        $start =  array_key_exists( 'start', $query)  ? (int)$query['start'] : 0;
        $length = array_key_exists( 'length', $query) ? (int)$query['length'] : null;
        $search = array_key_exists('search', $query) ? array_key_exists('value', $query['search']) ? (string)$query['search']['value'] : null : null;
        $order = array_key_exists('order', $query) && is_array( $query['order']) ? $query['order'] : null;
        $orderDir = !is_null($order) && count($order) > 0 ? array_key_exists('dir', $order[0]) ? (string)$order[0]['dir'] : null: null;
        $orderBy = !is_null($order) && count($order) > 0 ? array_key_exists('name', $order[0]) ? (string)$order[0]['name'] : null: null;
        $model = model(Item::class);
        /** @var Item $model */
        $list = $model->getItems($search, $length, $start, $orderBy, $orderDir);
        $result = array("recordsTotal" => $model->getItemsCount(null), "recordsFiltered" => $model->getItemsCount($search), "data" => $list, "query" => $query);
        $this->response->setHeader('content-type', 'application/json')->setBody(json_encode($result))->send();
    }

    /**
     * POST - "/items/checked"
     * @return void
     */
    public function checked(): void{
        $body = $this->request->getJSON(true);
        $id = array_key_exists('id', $body) ? (int)$body['id'] : null;
        $value = array_key_exists('value', $body) ? (bool)$body['value'] : null;
        $model = model(Item::class);
        /** @var Item $model */
        $success = $model->setChecked($id, $value);
        $this->response->setHeader('content-type', 'application/json')->setBody(json_encode(array("success"=>$success)))->send();
    }
}