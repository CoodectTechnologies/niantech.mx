<table class="table table-striped">
    <tbody>
        @foreach($product->productCharacteristics as $characteristic)
            <tr>
                <th scope="row">{{ $characteristic->key }}</th>
                <td>{{ $characteristic->value }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
