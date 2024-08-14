<div class="card">
    <div class="card-header">
        <h6>جلسات تسجيل الدخول للمستخدم: {{ $user->email }}</h6>
    </div>
    <div class="card-body">
        <table class="table text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th colspan="4">المدة</th>
                    <th>الوقت والتاريخ</th>
                </tr>
                <tr>
                    <th></th>
                    <th>يوم</th>
                    <th>ساعة</th>
                    <th>دقيقة</th>
                    <th>ثانية</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user->durations as $duration)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $duration->duration_in_days }}</td>
                        <td>{{ $duration->duration_in_hours }}</td>
                        <td>{{ $duration->duration_in_minutes }}</td>
                        <td>{{ $duration->duration_in_seconds }}</td>
                        <td>{{ $duration->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
