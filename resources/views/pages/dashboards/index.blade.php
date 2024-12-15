<x-default-layout>

    @section('title')
        Dashboard
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('dashboard') }}
    @endsection

    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <div class="col-xl-3">
        
        <!--begin::Statistics Widget 5-->
        <a href="#" class="card bg-warning hoverable card-xl-stretch mb-xl-8">
    <!--begin::Body-->
    <div class="card-body">
        <i class="ki-duotone ki-cheque text-gray-100 fs-2x ms-n1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span></i>        

        <div class="text-gray-100 fw-bold fs-2 mb-2 mt-5">           
            +{{$dash['customers']}}                  
        </div>

        <div class="fw-semibold text-gray-100">
           Customers       </div>
    </div>
    <!--end::Body-->
</a>
        <!--end::Statistics Widget 5-->    </div>
        <div class="col-xl-3">
        
        <!--begin::Statistics Widget 5-->
        <a href="#" class="card bg-primary hoverable card-xl-stretch mb-xl-8">
    <!--begin::Body-->
    <div class="card-body">
        <i class="ki-duotone ki-cheque text-gray-100 fs-2x ms-n1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span></i>        

        <div class="text-gray-100 fw-bold fs-2 mb-2 mt-5">           
            +{{$dash['items']}}                  
        </div>

        <div class="fw-semibold text-gray-100">
           Items       </div>
    </div>
    <!--end::Body-->
</a>
        <!--end::Statistics Widget 5-->    </div>
       
       
      
        <div class="col-xl-3">
        
        <!--begin::Statistics Widget 5-->
        <a href="#" class="card bg-dark hoverable card-xl-stretch mb-xl-8">
    <!--begin::Body-->
    <div class="card-body">
        <i class="ki-duotone ki-cheque text-gray-100 fs-2x ms-n1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span></i>        

        <div class="text-gray-100 fw-bold fs-2 mb-2 mt-5">           
            +{{$dash['materials']}}               
        </div>

        <div class="fw-semibold text-gray-100">
           Total Stock Materials     </div>
    </div>
    <!--end::Body-->
</a>
        <!--end::Statistics Widget 5-->    </div>

        <div class="col-xl-3">
        
        <!--begin::Statistics Widget 5-->
        <a href="#" class="card bg-success hoverable card-xl-stretch mb-xl-8">
    <!--begin::Body-->
    <div class="card-body">
        <i class="ki-duotone ki-cheque text-gray-100 fs-2x ms-n1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span></i>        

        <div class="text-gray-100 fw-bold fs-2 mb-2 mt-5">           
            +{{$dash['rented']}}               
        </div>

        <div class="fw-semibold text-gray-100">
           Total Rented Materials     </div>
    </div>
    <!--end::Body-->
</a>
        <!--end::Statistics Widget 5-->    </div>
    </div>
    <!--end::Row-->

   
</x-default-layout>
