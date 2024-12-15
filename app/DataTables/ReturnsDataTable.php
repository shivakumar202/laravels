<?php

namespace App\DataTables;

use App\Models\RentItemDetails;
use App\Models\ReturnItems;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ReturnsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'returns.action')
            ->rawColumns(['return_date', 'paid_amt','Duration','balance'])
            ->addColumn('item', function ($row) {
                return $row->rentItemDetail->material->name ?? 'No Material';
            })
            ->addColumn('paid_amt', function (ReturnItems $returnItems) {
                return $returnItems->paid_amt ;
            })
            ->addColumn('customer', function ($row) {
                return $row->customers ? $row->customers->name : 'No Customer';
            })
            ->addColumn('balance', function($row) {
                $class = $row->balance_amt < 0 ? 'text-danger' : 'text-success';
                return sprintf('<p class="fw-bold %s">%s</p>', $class, $row->balance_amt);
            })
            ->addColumn('rent_items', function ($row) {
                return $row->rentItemDetail->qty;
            })
            ->editColumn('return_date', function (ReturnItems $returnItems) {
                return Carbon::parse($returnItems->return_date)->format('d-m-Y');
            })
          
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at->format('d-m-Y');
            })
            ->addColumn('extra_days', function ($row) {
                $endDate = Carbon::parse($row->end_date);
                return $endDate->diffInDays(Carbon::today(), false);
            })
            ->setRowId('id');
    }

    public function query(ReturnItems $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['customers', 'rentItemDetail.material', 'rentItemDetail.returnitems' , 'rentItemDetail'])
            ->whereDate('updated_at', Carbon::today());
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('returns-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>")
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(1)
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('customer')->addClass('text-center'),
            Column::make('item')->addClass('text-center'),
            Column::make('rent_items')->addClass('text-center'),
            Column::make('qty')->title('Returned Quantity')->addClass('text-center'),
            Column::make('balance')->title('Balance Amount')->addClass('text-center'),
            Column::make('return_date')->addClass('text-center'),
            Column::make('paid_amt')->addClass('text-center'),
            Column::make('updated_at')->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Returns_' . date('YmdHis');
    }
}
