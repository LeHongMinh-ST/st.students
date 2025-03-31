<div>
    <div class="card">
        <div class="py-3 card-header">
            <div class="d-flex justify-content-between">

                <div class="flex-wrap gap-2 d-flex">
                    <div>
                        <span class="placeholder col-12"></span>
                    </div>
                </div>
                <div class="gap-2 d-flex">
                    <div>
                        <span class="placeholder col-12"></span>
                    </div>
                </div>
            </div>

        </div>

        <div class="table-responsive">
            <table class="table fs-table">
                <thead>
                    <tr class="table-light">
                        <th width="5%" class="text-center"><span class="placeholder col-7"></th>
                        <th width="30%"><span class="placeholder col-9"></span></th>
                        <th><span class="placeholder col-9"></span></th>
                        <th><span class="placeholder col-9"></span></th>
                        <th><span class="placeholder col-9"></span></th>
                        <th><span class="placeholder col-9"></span></th>

                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 5; $i++)
                        <tr>
                            <td class="text-center" width="5%"><span class="placeholder col-7"></td>
                            <td width="30%">
                                <span class="placeholder col-9">
                            </td>
                            <td><span class="placeholder col-9"></td>
                            <td><span class="placeholder col-9"></td>
                            <td><span class="placeholder col-9"></td>
                            <td><span class="placeholder col-9"></td>
                        </tr>
                    @endfor


                </tbody>
            </table>

        </div>
    </div>
</div>
