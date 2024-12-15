<?php

namespace App\DataTables;

use App\Models\Customers;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CustomersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->rawColumns(['isfav','profile'])
        ->editColumn('isfav', function(Customers $customers) {
            return sprintf(
                '<a href="#" class="bi bi-%s text-danger fs-2x" data-kt-customers-id="%s" data-kt-action="add_fav"></a>',
                $customers->isfav ? 'heart-fill' : 'heart',
                $customers->id
            );
        })
        ->addColumn('profile', function(Customers $customers) {
            if ($customers->customer_photo) {
                return sprintf('<img src="%s" class="symbol symbol-square symbol-50px overflow-hidden w-100">', asset('assets/media/' . $customers->customer_photo));
            } else {
                return '<span class="text-muted">Update Profile</span>';
            }
        })
        
            ->addColumn('action', function(Customers $customers) {
                return view('pages.apps.customer-management.new.columns._actions',compact('customers'));
            })
            ->editColumn('updated_at', function(Customers $customers) {
                return $customers->updated_at->format('d-m-y h:i A');
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Customers $model): QueryBuilder
    {
    
            $user = Auth::user();
            if(Auth::user()->roles->first()?->name === 'developer')
            {
                $query = $model->newQuery();
            } else {
               $query = $model->newQuery()->where('status',1); 
            }
            return $query;
        }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('customers-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>")
                    ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
                    ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
                    ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/customer-management/new/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
           
            Column::make('id'),
            Column::make('profile'),
            Column::make('name'),
            Column::make('phone_no'),
            Column::make('alt_mobile'),
            Column::make('id_type'),
            Column::make('id_no'),
        
            Column::make('updated_at')->title('Last Update'),
            Column::make('isfav'),
            Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->width(60)
            ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Customers_' . date('YmdHis');
    }
}