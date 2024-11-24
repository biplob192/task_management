<div class="table-rep-plugin">
    <div class="table-responsive mb-0" data-pattern="priority-columns">
        <table {{ $attributes->merge(['class' => 'table table-bordered table-striped']) }}>
            <thead>
                <tr>
                    {{ $head }}
                </tr>
            </thead>
            <tbody>
                {{ $body }}
            </tbody>
        </table>
    </div>
</div>