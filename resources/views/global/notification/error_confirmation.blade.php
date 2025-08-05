<div class="card-header shadow-none">
    <div class="alert" style="max-height:200px;overflow:auto;">
        <div class="row">
            @foreach($data['message'] as $k => $v)
            <div class="col-md-12 alert-warning mb-2 pl-2 pt-1 pl-3" style="border-radius: 6px;">
                <div class="row">
                    <div class="col-md-4">
                        <i class="fas fa-exclamation-triangle text-danger"></i> {{ ($data['translator'][$k]) ?? $k }}
                    </div>
                    <div class="col-md-6">
                        @if(!empty($v))
                            <ul style="list-style-type: none;">
                                @foreach($v as $k2 => $v2)
                                <li>
                                    {{$v2}}
                                </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="offset-md-2">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
