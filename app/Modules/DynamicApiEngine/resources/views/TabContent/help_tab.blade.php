<div class="panel panel-default">
    <div class="panel-body">
        <div class="table-responsive">

            <div class="card card-info border border-info" style="margin: 0px; padding: 0px;">
                <!-- Default panel contents -->
                <div class="card-header " style="height: 35px; background-color: #0d6aad; color: white;"><h5>Supported Authentication Methods</h5></div>
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <td class="col-lg-3">1. oAuth2.0</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="card card-info border border-info" style="margin: 0px; padding: 0px;">
                <!-- Default panel contents -->
                <div class="card-header" style="height: 35px; background-color: #0d6aad; color: white;"><h5>Available Patterns And Input Variables</h5></div>
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <td class="col-md-2">General: </td>
                        <td class="col-md-6"><code>{$variable_name}</code></td>
                        <td class="col-md-4"><i> To use data from requested parameter . Example: {$user_name} </i></td>
                    </tr>
                    <tr>
                        <td class="col-md-2">Multi-Dimensional: </td>
                        <td class="col-md-6"><code>{$obj->property_name}, {$obj->obj->property_name}</code></td>
                        <td class="col-md-4"><i> To use data from requested multi-dimensional parameter or raw (json) . Example: {$data->user_name}  </i></td>
                    </tr>
                    <tr>
                        <td class="col-md-2">With JSON encode: </td>
                        <td class="col-md-6"><code>_je{$variable_name}, _je{$obj->property_name}, _je{$obj->obj->property_name}</code></td>
                        <td class="col-md-4"><i> To JSON encode requested parameter data for process</i></td>
                    </tr>
                    </tbody>
                </table>
            </div>


            <div class="card card-info border border-info" style="margin: 0px; padding: 0px;">
                <!-- Default panel contents -->
                <div class="card-header" style="height: 35px; background-color: #075698; color: white;"><h5>Set Of Operation Variables</h5></div>
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <td class="col-lg-2">General: </td>
                        <td class="col-lg-6"><code>{#operation_key->property_name#}  &nbsp;  Example : {#user_table->id#}'</code></td>
                        <td class="col-lg-4"><i> To use any operational object </i></td>
                    </tr>
                    <tr>
                        <td class="col-lg-2">Get inserted data id: </td>
                        <td class="col-lg-6"><code>{#operation_key->id#}   &nbsp;  Example : _je{$data->master_frm_id}'</code></td>
                        <td class="col-lg-4"><i> To use any inserted id based on insert query </i></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="card card-info border border-info" style="margin: 0px; padding: 0px;">
                <!-- Default panel contents -->
                <div class="card-header" style="height: 35px; background-color: #075698; color: white;"><h5>Output json </h5></div>
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <td class="col-lg-2">Nested: </td>
                        <td class="col-lg-6"><code>#SQ_OutputSqlID#   &nbsp;  Example : #SQ_1#</code></td>
                        <td class="col-lg-4"><i> To create nested json response </i></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="card card-info border border-info" style="margin: 0px; padding: 0px;">
                <!-- Default panel contents -->
                <div class="card-header" style="height: 35px; background-color: #075698; color: white;"><h5>Supported validation rules</h5></div>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th scope="col" class="col-xs-3">Rule</th>
                        <th scope="col" class="col-xs-4">Example</th>
                        <th scope="col" class="col-xs-5">Comment</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>SQL</td>
                        <td><code>SELECT id FROM users WHERE email = '{$email'</code></td>
                        <td><i> To validate with data based on sql </i></td>
                    </tr>
                    <tr>
                        <td>EMAIL</td>
                        <td> <code>xyz@gmail.com</code> </td>
                        <td><i> Only allowed valid email </i></td>
                    </tr>
                    <tr>
                        <td>REQUIRED</td>
                        <td></td>
                        <td> <i> Data is mandatory </i> </td>
                    </tr>
                    <tr>
                        <td>NUMBER</td>
                        <td> <code>12345</code> </td>
                        <td> <i> Data should be number </i> </td>
                    </tr>
                    <tr>
                        <td>MIN:NUMBER</td>
                        <td> </td>
                        <td> <i>Minimum length of data</i> </td>
                    </tr>
                    <tr>
                        <td>MAX:NUMBER</td>
                        <td></td>
                        <td> <i>Maximum length of data</i> </td>
                    </tr>
                    <tr>
                        <td>URL</td>
                        <td><code>https://example.org</code></td>
                        <td> <i> Data should be url </i> </td>
                    </tr>
                    <tr>
                        <td>LENGTH:NUMBER</td>
                        <td></td>
                        <td><i>Fixed length of data</i></td>
                    </tr>
                    <tr>
                        <td>LENGTH_BETWEEN: MIN, MAX</td>
                        <td></td>
                        <td><i> Data character range </i></td>
                    </tr>
                    <tr>
                        <td>DATE:FORMAT</td>
                        <td></td>
                        <td><i> Valid date format </i></td>
                    </tr>
                    <tr>
                        <td>BOOLEAN</td>
                        <td> <code>TRUE</code></td>
                        <td> <i> Data should be boolean </i> </td>
                    </tr>
                    <tr>
                        <td>ALPHA</td>
                        <td><code>ABCD</code></td>
                        <td><i> Data should be alphabetic </i></td>
                    </tr>
                    <tr>
                        <td>ALPHA_NUM</td>
                        <td><code>ABCD12345</code></td>
                        <td><i> Data should be alphabetic or number </i></td>
                    </tr>
                    <tr>
                        <td>ALPHA_DASH</td>
                        <td><code>ABCD12345_</code></td>
                        <td><i> Data should be alphabetic or number or special character </i></td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

