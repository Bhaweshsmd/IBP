@extends('include.app')
@section('content')

    <div class="card">
        <div class="card-header">
            <h4>{{ __('Support Ticket Details') }}</h4>
            <a  href="{{route('tickets')}}" class="ml-auto btn btn-primary text-white"><i class="fa fa-chevron-left"></i> Back to Support Tickets List</a>
        </div>
        <div class="card-body">
            <div class="container">
                <div class="row box">
                    <div class="col-md-9">
                        <h6 class="mb-3"><strong>Subject : </strong> {{$tickets->subject}}</h6>
                        <h6 class="mb-3"><strong>Reason : </strong> {{$tickets->reason}}</h6>
                    </div>
                    <div class="col-md-3 text-right">
                        <h6 class="mb-2"><strong>Ticket Status : </strong> {{ucfirst($tickets->status)}}</h6>
                        <p class="label label-default mb-1" style="font-size: 14px">Priority : {{$tickets->priority}}</p>
                        <p class="label label-info mb-1" style="font-size: 14px">Ticket ID : {{$tickets->ticket_id}}</p>
                    </div>
                </div>
                <form action="{{ route('reply.ticket', $tickets->id) }}" method="post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="box-body">
                                <textarea type="text" name="message" class="summernote-simple form-control" id="summernote" required></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <input type="file" name="attachment" class="form-control mt-3" id="exampleFormControlFile1">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary mt-3" id='submitformbtn'>Reply</button>
                        </div>
                    </div>
                </form>
                
                <form action="{{ route('ticket.status', $tickets->id) }}" method="post" enctype="multipart/form-data" onsubmit='disableformButton()'>
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="exampleFormControlFile1">Change Status</label>
                            <select class="form-control" name="status" required>
                                <option value="open" @if($tickets->status == 'open') selected @endif>Open</option>
                                <option value="inprogress" @if($tickets->status == 'inprogress') selected @endif>In Progress</option>
                                <option value="hold" @if($tickets->status == 'hold') selected @endif>Hold</option>
                                <option value="closed" @if($tickets->status == 'closed') selected @endif>Closed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary mt-4" id='submitformbtn'>Submit</button>
                        </div>
                    </div>
                </form>
    
                @foreach($ticket_details as $ticket_detail)
                    @if($ticket_detail->type == 'user')
                        <div class="box">
                            <div class="row box-body" style="background-color: #FFFFE6; margin-bottom: 10px;">
                                <div class="col-sm-1">
                                    <a href="{{ route('users.profile', $tickets->user_id) }}">
                                        <?php echo $profile_image; ?>
                                    </a>
                                </div>
                                <div class="col-sm-11">
                                    <p><?php echo $ticket_detail->message; ?></p>
                                </div>
                            
                            <div class="box-footer">
                                <span><i class="fa fa-fw fa-clock-o"></i><small><i>{{ Carbon\Carbon::parse($ticket_detail->created_at)->format('d-M-Y') }}</i></small></span>
                            </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($ticket_detail->type == 'admin')
                        <div class="box">
                            <div class="row box-body" style="background-color: #F2F4F4; margin-bottom: 10px;">
                                <div class="col-sm-10" style="margin-top: 10px; text-align: right;">
                                    <p><?php echo $ticket_detail->message; ?></p>
                                </div>
                                <div class="col-sm-2 text-right">
                                    <a href="{{ route('admins.profile') }}">
                                        <?php echo $admin_image; ?>
                                    </a><br>
                                    <i class="fa fa-fw fa-clock-o"></i><small><i>{{ Carbon\Carbon::parse($ticket_detail->created_at)->format('d-M-Y') }}</i></small>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    
    @if(session()->has('ticket_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('ticket_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
@endsection
