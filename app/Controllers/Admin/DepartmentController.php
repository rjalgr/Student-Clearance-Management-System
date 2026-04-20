<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\DepartmentModel;

class DepartmentController extends BaseController
{
    protected DepartmentModel $deptModel;
    public function __construct() { $this->deptModel = new DepartmentModel(); }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'code' => 'required|is_unique[departments.code]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->to('/admin/dashboard#section-departments')
                             ->with('errors', $this->validator->getErrors());
        }
        $this->deptModel->insert($this->request->getPost(['name','code','head_name','description']));
        return redirect()->to('/admin/dashboard#section-departments')->with('success','Department added.');
    }

    public function update(int $id)
    {
        $this->deptModel->update($id, $this->request->getPost(['name','code','head_name','description','is_active']));
        return redirect()->to('/admin/dashboard#section-departments')->with('success','Department updated.');
    }

    public function delete(int $id)
    {
        $this->deptModel->delete($id);
        return redirect()->to('/admin/dashboard#section-departments')->with('success','Department deleted.');
    }
}