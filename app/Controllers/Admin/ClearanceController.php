<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\ClearanceRequestModel;
use App\Models\ClearanceItemModel;

class ClearanceController extends BaseController
{
    public function view(int $id)
    {
        $requestModel = new ClearanceRequestModel();
        $itemModel    = new ClearanceItemModel();
        $request      = $requestModel->getWithStudent($id);
        $items        = $itemModel->getItemsForRequest($id);
        return view('admin/clearance_view', compact('request','items'));
    }

    public function update(int $id)
    {
        $requestModel = new ClearanceRequestModel();
        $status       = $this->request->getPost('overall_status');
        $requestModel->update($id, ['overall_status' => $status]);
        return redirect()->to('/admin/dashboard#section-clearances')->with('success','Clearance status updated.');
    }

    public function delete(int $id)
    {
        $requestModel = new ClearanceRequestModel();
        $requestModel->delete($id);
        return redirect()->to('/admin/dashboard#section-clearances')->with('success','Clearance request deleted.');
    }
}