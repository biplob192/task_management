<div {{ $attributes->merge(['class' => 'row mt-3']) }}>
    <div class="col-sm">
        <div class="mb-4">
            {{ $left ?? null }}
        </div>
    </div>

    <div class="col-sm-auto">
        <div class="d-flex align-items-center gap-1 mb-4">
            {{ $right ?? null }}
        </div>
    </div>
</div>