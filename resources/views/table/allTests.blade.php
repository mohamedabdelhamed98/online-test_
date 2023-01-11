@extends('table.main')
@section('studentContent')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
         <!--  <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <span data-feather="calendar"></span>
            This week
          </button> -->
        </div>
      </div>



      <div class="table-responsive">
        @include('addons.flash')

        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Exam Availability</th>
              <th>Exam Start Date</th>
              <th>Deadline</th>
              <th>Register</th>
              <th>Start Exam</th>

            </tr>
          </thead>
          <tbody>
              @foreach ($subjects as $subject )

              @if ($subject->exam_deadline >date('Y-m-d H:i:s') )

                        <tr>
                            <td>{{ $subject->id}}</td>
                            <td>{{ $subject->name }}</td>
                            @if ($subject->exxam_availability == 1)
                            <td>yes</td>
                            @else
                            <td>no</td>
                            @endif
                            <td>{{ $subject->exam_start_date }}</td>
                            <td>{{ $subject->exam_deadline }}</td>
                            @if ($subject->exam_deadline > date('Y-m-d H:i:s'))

                            <td> <a class="btn btn-primary btn-sm" href="{{route('registerExam',['subject_id'=>$subject->id])  }}">Register</a>   </td>

                        @else
                        <td>deadline passed</td>
                        @endif
                            @if ($subject->exam_start_date > date('Y-m-d H:i:s'))
                            <td></td>

                            @elseif ($subject->exam_deadline > date('Y-m-d H:i:s'))

                            <td> <a class="btn btn-danger btn-sm" href="{{ route('getTestQuestions',['subject_id'=>$subject->id]) }}">Start Exam</a>   </td>
                        @else
                        <td>deadline passed</td>

                @endif
              </tr>


              @else
              <div></div>

                @endif

              @endforeach

          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>


@endsection

