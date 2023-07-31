
<tr>
    <td>{{ data_get($model, 'id') }}</td>
    <td>{{ data_get($model, 'name') }}</td>
    <td class="text-right">
        <ul class="list-inline">
            <li><a href="{{ route('backend.categories.edit', ['id' => data_get($model, 'id')]) }}" class="btn btn-xs btn-primary">Edit</a></li>
        </ul>
    </td>
</tr>
