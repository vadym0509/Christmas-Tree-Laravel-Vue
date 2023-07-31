
<tr>
    <td>{{ $model->id }}</td>
    <td class="delete-column">{!! Form::checkbox('mass_delete[]', $model->id) !!}</td>
    <td>
        <a href="{{ route('backend.promotions.edit', ['id' => $model->id]) }}">{{ str_limit($model->title, 40) }}</a>
    </td>
    <td>{{ data_get($model, 'kilted-trees', 'n/a') ? 'Yes' : 'No' }}</td>
    <td>{{ data_get($model, 'festive-floristry', 'n/a') ? 'Yes' : 'No' }}</td>
    <td class="text-right">
        <ul class="list-inline">
            <li><a href="{{ route('backend.promotions.edit', ['id' => $model->id]) }}" class="btn btn-xs btn-primary">Edit</a></li>
            <li>
                @include('hone::backend._shared.modals.delete_trigger', [
                    'modal_action' => route('backend.promotions.destroy', ['id' => $model->id])
                ])
            </li>
        </ul>
    </td>
</tr>
