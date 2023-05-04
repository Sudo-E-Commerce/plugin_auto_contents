<div class="rewrite_box" id="{{ $name }}_rewrite_box" data-field_title="{{$field_title}}" style="display: none">
    <div class="rewrite_box__header">
        <div class="rewrite_box__header__top">
            <span class="title"><i class="fa fa-paint-brush"></i> Rewrite</span>
            <span class="redo">
                <i class="fa fa-undo"></i> Redo
            </span>
        </div>
        <div class="rewrite_box__header__bottom">
            <input type="hidden" class="selected-text">
            <input type="hidden" class="current-type">

            <div class="form-inline text-center">
                @php
                    $type_rewrite = getOption('type_rewrite');
                    $type_rewrite = collect($type_rewrite['type_rewrite'] ?? [])->map(function($item) {
                        return (object) $item;
                    });
                    $type_rewrite = $type_rewrite->pluck('title', 'key')->toArray();
                @endphp
                <div class="form-group">
                    <select class="type-rewrite form-control input-sm">
                        @foreach($type_rewrite as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-sm btn-go btn-primary">Go</button>
                </div>
            </div>
        </div>
    </div>
    <div class="rewrite_box__body">
        <div class="rewrite_result_list">
        </div>
    </div>
    <div class="rewrite_box__footer"></div>

</div>
