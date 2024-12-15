<?php

namespace App\DataTables;

use App\Models\Materials;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MaterialsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['image',['available']])
            ->editColumn('available', function(Materials $materials) {
                $totalBalance = $materials->rentItemDetails()->where('status', 1)->sum('balance');        
                return $materials->qty - $totalBalance;
            })


            
          
            ->editColumn('image', function(Materials $materials) {
                return sprintf('<img src="%s" class="symbol symbol-square symbol-100px overflow-hidden me-3 w-50">',asset('assets/media/'.$materials->image));
            })
            ->addColumn('action', function(Materials $materials) {
                return view('pages.apps.godown-management.columns._actions',compact('materials'));
            })
            ->editColumn('updated_at', function(Materials $materials) {
                return $materials->updated_at->format('d-m-y h:i A');
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Materials $model): QueryBuilder
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
                    ->setTableId('materials-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>")
                    ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
                    ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
                    ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/godown-management/columns/_draw-scripts.js')) . "}");

    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            
            Column::make('id'),
            Column::make('image'),
            Column::make('name'),
            Column::make('description'),
            Column::make('qty')->title('TOTAL QUANTITY'),           
            Column::make('available')->name('updated_at'),        
            Column::make('updated_at')->title('LAST UPDATE'),
          
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
        return 'Materials_' . date('YmdHis');
    }
}