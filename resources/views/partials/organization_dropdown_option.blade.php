    <option value="" selected>Select</option>
    @forelse ($org_dropdowns as $organization)
    @if (request()->input('o_id'))
    <option value="{{ $organization->id }}" selected>{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
    @else
    <option value="{{ $organization->id }}"> {{ $organization->id }} - {{ isset($organization->branch_code) ? $organization->branch_code . ' - ' :'' }} {{ $organization->name }} {{ isset($organization->branch_name) ? ' - ' . $organization->branch_name : '' }}</option>
    @endif
    @empty
    <option value="">Please select</option>
    @endforelse
