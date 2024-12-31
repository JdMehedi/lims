@extends('public_home.front')
@section('body')
    <div style="max-width: 1140px; margin: 16px auto 0 auto; min-height: calc(100vh - 156px);">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">SL</th>
                    <th scope="col">License Name</th>
                    <th scope="col" style="text-align: center">Download User Manual</th>
                </tr>
                </thead>
                <tbody>
                @forelse($user_manuals as $key => $user_manual)
                    <tr>
                        <td>{{ $user_manuals->firstItem() + $key }}</td>
                        <td>{{ $user_manual->typeName }}</td>
                        <td style="text-align: center">
                            <a href="{{ $user_manual->pdfFile }}" class="btn btn-xs btn-success">
                                Open
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center">No user manuals found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $user_manuals->links() }}
        </div>
    </div>

    </div>
@endsection
