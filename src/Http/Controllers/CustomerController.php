<?php

namespace Lmate\Customer\Http\Controllers;

use Lmate\Customer\Models\Customer;
use Lmate\Customer\Models\Addon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lmate\Customer\Http\Requests\SimpleFormRequest;
use Lmate\Customer\Exports\CustomerExport;
use Maatwebsite\Excel\Facades\Excel;
use Lmate\Customer\Mail\ContactMailable;
use Illuminate\Support\Facades\Mail;
use Cache;

use Lmate\Customer\Models\{State, Country, City};

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = "";
        $status = "";
        $selectedColumns = [];
        if ($request->isMethod('post')) {
            $filter = $request->input('filter');
            $status = $request->input('status');
            if (!empty($request->selectedColumns)) {
                foreach ($request->selectedColumns as $selectedColumn) {
                    $selectedColumns[] = $selectedColumn;
                }
            }
            if (!empty($filter) && $status != "") {
                $data = Customer::sortable()
                    ->where('customer.status', '=', $status)
                    ->where('customer.firstname', 'like', '%' . $filter . '%')
                    ->paginate(5);
            } elseif (!empty($filter) && $status == "") {
                $data = Customer::sortable()
                    ->where('customer.firstname', 'like', '%' . $filter . '%')
                    ->orWhere('customer.lastname', 'like', '%' . $filter . '%')
                    ->paginate(5);
            } elseif (empty($filter) && $status != "") {
                $data = Customer::sortable()
                    ->where('customer.status', '=', $status)
                    ->paginate(5);
            } else {
                $data = Customer::sortable()
                    ->paginate(5);
            }
        } else {
            $data = Customer::sortable()->paginate(5);
        }
        return view('customer::customer.index', compact('data'))->with('filter', $filter)->with('status', $status)->with('selectedColumns', $selectedColumns)
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchFilter(Request $request)
    {
        $filter = "";
        $status = "";
        $selectedColumns = [];
        if ($request->isMethod('post')) {
            $filter = $request->input('filter');
            $status = $request->input('status');
            if (!empty($request->selectedColumns)) {
                foreach ($request->selectedColumns as $selectedColumn) {
                    $selectedColumns[] = $selectedColumn;
                }
            }
            if (!empty($filter) && $status != "") {
                $data = Customer::sortable()
                    ->where('customer.status', '=', $status)
                    ->where('customer.firstname', 'like', '%' . $filter . '%')
                    ->paginate(5);
            } elseif (!empty($filter) && $status == "") {
                $data = Customer::sortable()
                    ->where('customer.firstname', 'like', '%' . $filter . '%')
                    ->orWhere('customer.lastname', 'like', '%' . $filter . '%')
                    ->paginate(5);
            } elseif (empty($filter) && $status != "") {
                $data = Customer::sortable()
                    ->where('customer.status', '=', $status)
                    ->paginate(5);
            } else {
                $data = Customer::sortable()
                    ->paginate(5);
            }
        } else {
            $data = Customer::sortable()->paginate(5);
        }
        return view('customer::customer.index', compact('data'))->with('filter', $filter)->with('status', $status)->with('selectedColumns', $selectedColumns)
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::get(["name", "id"]);
        return view('customer::customer.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'customer_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'filename' => 'required',
            'filename.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $requestData = $request->all();
        $requestData['devices'] = implode(',', $request->devices);
        $requestData['subscription'] = isset($request->subscription) ? $request->subscription : 0;
        $product_images = array();
        $fileNameData = array();
        if ($request->hasFile('customer_image')) {
            $imageName = time() . '.' . $request->customer_image->extension();
            $request->customer_image->move(public_path('customer_images'), $imageName);
            $requestData['customer_image'] = $imageName;
        }
        if ($files = $request->file('product_images')) {
            foreach ($files as $file) {
                $name = $file->getClientOriginalName();
                $file->move(public_path('images/product_images'), $name);
                $product_images[] = $name;
            }
        }
        $requestData['product_images'] = implode(',', $product_images);
        if ($request->hasFile('filename')) {
            foreach ($request->file('filename') as $image) {
                $name = $image->getClientOriginalName();
                $image->move(public_path() . '/images/', $name);
                $fileNameData[] = $name;
            }
        }
        $requestData['filename'] = implode(',', $fileNameData);
        $customer_id = Customer::create($requestData)->id;
        $processedAddmoreInputFields = [];
        $processedInputData = [];
        foreach ($requestData['addMoreInputFields'] as $key => $addmoredata) {
            $processedAddmoreInputFields[] = $addmoredata;
        }
        foreach ($processedAddmoreInputFields as $key => $processedInput) {
            $processedInputData['addon_name'] = $processedInput['name'];
            $processedInputData['addon_desc'] = $processedInput['description'];
            $processedInputData['customer_id'] = $customer_id;
            Addon::create($processedInputData);
        }
        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        $customer->product_images = explode(',', $customer->product_images);
        $customer->filename = explode(',', $customer->filename);
        $customer->addMoreInputFields = [];
        //print_r($customer->product_images);exit;
        return view('customer::customer.show', compact('customer', $customer));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        $customer->product_images = explode(',', $customer->product_images);
        $customer->filename = explode(',', $customer->filename);
        $customer->devices = explode(',', $customer->devices);
        $data_arr = [];
        $addonData = Addon::where('customer_id', '=', $customer->id)->get();
        foreach ($addonData as $addon) {
            $data_arr[] = (object)[
                "name" => $addon->addon_name,
                "description" => $addon->addon_desc
            ];
        }
        $customer->addMoreInputFields = $data_arr;
        return view('customer::customer.edit', compact('customer', $customer));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'customer_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'filename' => 'required',
            'filename.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $requestData = $request->all();
        $requestData['devices'] = implode(',', $request->devices);
        $requestData['subscription'] = isset($request->subscription) ? $request->subscription : 0;
        $product_images = array();
        $fileNameData = array();
        if ($request->hasFile('customer_image')) {
            $imageName = time() . '.' . $request->customer_image->extension();
            $request->customer_image->move(public_path('customer_images'), $imageName);
            $requestData['customer_image'] = $imageName;
        }
        if ($files = $request->file('product_images')) {
            foreach ($files as $file) {
                $name = $file->getClientOriginalName();
                $file->move(public_path('images/product_images'), $name);
                $product_images[] = $name;
            }
            $requestData['product_images'] = implode(',', $product_images);
        }

        if ($request->hasFile('filename')) {
            foreach ($request->file('filename') as $image) {
                $name = $image->getClientOriginalName();
                $image->move(public_path() . '/images/', $name);
                $fileNameData[] = $name;
            }
            $requestData['filename'] = implode(',', $fileNameData);
        }
        $customer->update($requestData);
        return redirect()->route('customers.index')
            ->with('success', 'Customer udpated successfully.');
    }

    /**
     * Status update for the specified resource in storage
     */
    public function statusUpdate(Request $request)
    {
        $customerId = $request->customerId;
        $customerStatus = $request->customerStatus;
        $updateStatus = ($customerStatus == 1) ? 0 : 1;
        $customer = Customer::find($customerId);
        $customer->status = $updateStatus;
        $customer->save();
        echo $customer->status;
        exit;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->obsolete = 1;
        $customer->update();
        return redirect()->route('customers.index')
            ->with('success', 'Customer Deleted successfully.');
    }

    /**
     * Function for the Export data to Excel
     */
    public function get_customer_data(Request $request)
    {
        $filterName = $request->get('filterName');
        $statusFilter = $request->get('statusFilter');
        $columnFilter = $request->get('columnFilter');
        $extension = $request->get('extension');
        if ($extension == "") {
            return Excel::download(new CustomerExport($filterName, $statusFilter, $columnFilter), 'customers.xlsx');
        } else {
            return Excel::download(new CustomerExport($filterName, $statusFilter, $columnFilter), 'customers.' . $extension);
        }
    }

    public function fetchStatesOrCity(Request $request)
    {
        if ($request->has('req_type')) {
            $data = array();
            if ($request->get('req_type') == 'state') {
                $data['states'] = State::where('country_id', $request->country_id)->get(["name", "id"]);
            }
            if ($request->get('req_type') == 'city') {
                if ($request->has('state_id')) {
                    $data['cities'] = City::where('state_id', $request->state_id)->get(["name", "id"]);
                } else {
                    $data['cities'] = City::get(["name", "id"]);
                }
            }
            return response()->json($data);
        }
    }

    public function searchCities(Request $request)
    {
        if ($request->has('search')) {
            $data = City::where('name', 'like', '%' . $request->get('search') . '%')->get();
            $response = array();
            foreach ($data as $row) {
                $response[] = array("value" => $row['id'], "label" => $row['name']);
            }
            return  json_encode($response);
        }
    }

    public function updateCustomerStatus(Request $request)
    {
        if ($request->has("ids")) {
            $arr_ids = $request->get('ids');
            foreach ($arr_ids as $id) {
                $prevData = Customer::where('id', $id)->first();
                $prevData->status = $prevData->status == '1' ? '0' : '1';
                $prevData->save();
            }
            return "true";
        }
    }
}
