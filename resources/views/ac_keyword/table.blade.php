@include('Table::components.link',['text' => $value->primary_keyword, 'url' => route('admin.ac_keywords.edit', $value->id )])
@include('Table::components.text',['text' => $value->sub_keyword])
<td><a href="{{ route('admin.ac_outline', $value->id) }}"><strong>Thêm / sửa dàn ý <i class="fa fa-share-square"></i></strong></a></td>