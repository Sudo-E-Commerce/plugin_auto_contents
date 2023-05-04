<div class="settings-sidebar">
	<ul>
		<li @if($setting_name == 'general_ai') class="active" @endif><a href="{!! route('admin.settings.general_ai') !!}">@lang('Tư duy cho AI')</a></li>
		<li @if($setting_name == 'type_heading') class="active" @endif><a href="{!! route('admin.settings.type_heading') !!}">@lang('Heading content')</a></li>
		<li @if($setting_name == 'type_rewrite') class="active" @endif><a href="{!! route('admin.settings.type_rewrite') !!}">@lang('Heading rewrite')</a></li>
		<li @if($setting_name == 'type_write') class="active" @endif><a href="{!! route('admin.settings.type_write') !!}">@lang('Heading write')</a></li>
	</ul>
</div>