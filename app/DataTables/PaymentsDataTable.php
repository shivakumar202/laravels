<?php

namespace App\DataTables;

use App\Models\Payment;
use App\Models\RentDetails;
use App\Models\RentItemDetails;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PaymentsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->rawColumns(['customer','item','balance_amt'])
        ->addColumn('customer' , function($row) {
            return $row->customers->name;
        })
        ->addColumn('item', function($row) {
            return $row->material->name;
        })
        ->editColumn('updated_at',function($row) {
            return $row->updated_at->format('d-m-Y h:i A');
        })
        ->editColumn('status', function($row){
            return $row->status == 0? 'Collected' : 'Pending'; 
        })
        ->addColumn('balance_amt', function($row) {
            $class = $row->balance_amt < 0 ? 'text-danger' : 'text-success';
            return sprintf('<p class="fw-bold %s">%s</p>', $class, $row->balance_amt);
        })
        
        ->addColumn('action', function(RentItemDetails $rentItemDetails) {
            return view('pages.apps.customer-management.existing.columns._actions',compact('rentItemDetails'));
        })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
  
    public function query(RentItemDetails $model): QueryBuilder
    {
        return $model->with(['customers', 'rentDetail', 'material'])
            ->newQuery()
            ->where(function ($query) {
                $query->where('balance', '!=', 0)
                    ->orWhere('status', '!=', 0)
                    ->orWhere('balance_amt', '!=', 0);
            })
            ->orWhere(function ($query) {
                $query->where(function ($subQuery) {
                    // For 'days', fetch after one day
                    $subQuery->where('for', 'day')
                        ->whereDate('duration', '<', Carbon::now()->subDay());
                })
                ->orWhere(function ($subQuery) {
                    // For 'weeks', fetch after one week
                    $subQuery->where('for', 'week')
                        ->whereDate('duration', '<', Carbon::now()->subWeek());
                })
                ->orWhere(function ($subQuery) {
                    // For 'months', fetch after one month
                    $subQuery->where('for', 'month')
                        ->whereDate('duration', '<', Carbon::now()->subMonth());
                });
            });
    }
    
    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('payments-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>")
                    ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
                    ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
                    ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/customer-management/existing/columns/_draw-scripts.js')) . "}");
}

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            
            Column::make('id'),
            Column::make('customer')->addClass('text-center'),
            Column::make('item')->addClass('text-center'),
            Column::make('qty')->title('Rented Items')->addClass('text-center'),
            Column::make('balance')->title('Balance Items')->addClass('text-center'),
            Column::make('for')->addClass('text-center'),
            Column::make('duration')->title('Rent Date')->addClass('text-center'),
            Column::make('cost')->addClass('text-center'),
            Column::make('advance')->addClass('text-center'),
            Column::make('balance_amt')->addClass('text-center'),
            Column::make('status')->addClass('text-center'),
            Column::make('updated_at')->addClass('text-center'),
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
        return 'Payments_' . date('YmdHis');
    }
}
