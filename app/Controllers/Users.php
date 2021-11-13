<?php

namespace App\Controllers;

use App\Libraries\Crud;

//use function PHPUnit\Framework\returnSelf;

class Users extends BaseController
{

    protected $crud;

    function __construct()
    {
        $params = [
            "table" => "users",
            "dev" => false,
            "fields" => $this->field_options(),
            "form_title_add" => "Add User",
            "form_title_update" => "Edit User",
            "form_submit" => "Add",
            "table_title" => "Users",
            "form_submit_update" => "Update",
            "base" => "",
        ];

        $this->crud = new Crud($params, service("request"));
    }

    public function index()
    {
        $page = 1;
        if (isset($_GET["page"])) {
            $page = (int) $_GET["page"];
            $page = max(1, $page);
        }


        $data["title"] = $this->crud->getTableTitle();
        $per_page = 10;
        $columns = [
            "u_id",
            "u_firstname",
            "u_lastname",
            "u_email",
            "u_status"
        ];

        $where = null;  //["u_status -" => "Active"];

        $order = [
            ["u_id", "ASC"]
        ];

        $data["table"] = $this->crud->view(
            $page,
            $per_page,
            $columns,
            $where,
            $order
        );

        return view('admin/users/table', $data);
    }

    public function add()
    {
        $data["form"] = $form = $this->crud->form();
        $data["title"] = $this->crud->getAddTitle();

        if (is_array($form) && isset($form["redirect"]))
            return redirect()->to($form["redirect"]);

        return view("admin/users/form", $data);
    }

    public function edit($id)
    {
        if (!$this->crud->current_values($id))
            return redirect()->to($this->crud->getBase() . "/" . $this->crud->getTable());

        $data["item_id"] = $id;
        $data["form"] = $form = $this->crud->form();
        $data["title"] = $this->crud->getEditTitle();

        if (is_array($form) && isset($form["redirect"]))
            return redirect()->to($form["redirect"]);

        return view("admin/users/form", $data);
    }

    protected function field_options()
    {
        $fields = [];

        $fields["u_id"]         = [
            "label"         => "ID"
        ];

        $fields["u_firstname"]  = [
            "label"         => "First Name",
            "required"      => true,
            "helper"        => "Type your first name",
            "class"         => "col-12 col-sm-6"
        ];

        $fields["u_lastname"]   = [
            "label"         => "Last Name",
            "required"      => true,
            "helper"        => "Type your last name",
            "class"         => "col-12 col-sm-6"
        ];

        $fields["u_email"]      = [
            "label"         => "Email",
            "required"      => true,
            "unique"        => [true, "u_email"]
        ];

        $fields["u_password"]   = [
            "label"         => "Password",
            "only_add"      => true,
            "type"          => "password",
            "class"         => "col-12 col-sm-6",
            "confirm"       => true,
            "password_hash" => true
        ];

        $fields["u_status"]     = [
            "label"         => "Status",
            "required"      => true,
        ];

        $fields["u_created_at"] = [
            "label"         => "Created at",
            "only_edit"     => true
        ];

        return $fields;
    }
}
