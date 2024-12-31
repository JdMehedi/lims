@if(Auth::user()->user_type == '5x505')
    <div class="card card-default" >
        <div class="card-header">
            <div class="row">
                <div class="col-lg-6">
                    <h5 class="card-title"><i class="fa fa-list"></i> <b> {{trans('CompanyProfile::messages.company_list')}} <span
                                    class="list_name"></span>
                        </b>
                    </h5>
                </div>
                <div class="col-lg-6">
                    <button onclick="RequestCompany()"  class="btn btn-default float-right">
                        <i class="fa fa-plus"></i> <strong>{{trans('CompanyProfile::messages.company_association_new')}}</strong>
                    </button>

                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="companyAssociationList" style="width: 100%">
                    <thead>
                    <tr>
                        <th>{{trans('CompanyProfile::messages.company_owner_email')}}</th>
                        <th>{{trans('CompanyProfile::messages.company_name')}}</th>
                        <th>{{trans('CompanyProfile::messages.action')}}</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
            </div>

            <div id="load_content">
            </div>
        </div>
    </div>
@endif
