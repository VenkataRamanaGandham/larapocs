<?php

namespace Lmate\Customer\Exports;

use Lmate\Customer\Models\Customer;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CustomerExport implements FromCollection,WithHeadings
{
    
    protected $filterName;
    protected $statusFilter;
    protected $columnFilter = [];

    function __construct($filterName,$statusFilter,$columnFilter) {
           $this->filterName = $filterName;
           $this->statusFilter = $statusFilter;
           if(!empty($columnFilter)) {
            foreach($columnFilter as $key=>$val) {
                $this->columnFilter[] = $val;
            }
            }
    }
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function headings():array{
        if(!empty($this->columnFilter)) {
            return $this->columnFilter;
        } else {
        return[
            'Id',
            'FirstName',
            'LastName',
            'Email',
            'DOB',
            'Status',
            'Address',
            'Gender',
            'Devices'
        ];
    }
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if (empty($this->columnFilter)) {
            $this->columnFilter[] = "*";
        }
        if (!empty($this->filterName) && $this->statusFilter != "") {
            $customer = Customer::where('customer.status', '=', $this->statusFilter)
            ->where('customer.firstname', 'like', '%'.$this->filterName.'%')
            ->select($this->columnFilter)->get();
        } elseif (!empty($this->filterName) && $this->statusFilter == "") {
            $customer = Customer::where('customer.firstname', 'like', '%'.$this->filterName.'%')
            ->orWhere('customer.lastname', 'like', '%'.$this->filterName.'%')
            ->select($this->columnFilter)->get();
        } elseif (empty($this->filterName) && $this->statusFilter != "") {
            $customer = Customer::where('customer.status', '=', $this->statusFilter)
            ->select($this->columnFilter)->get();
        } else {
            $customer = Customer::select($this->columnFilter)->get();
        }
        return $customer;
    }
}
