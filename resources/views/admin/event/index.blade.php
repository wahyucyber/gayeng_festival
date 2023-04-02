@extends('_layout.app', [
    'dashboard' => true,
    'title' => "Event"
])

@section('content')
    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Fugiat dolore nobis, quis facilis error tenetur ratione veritatis rerum ducimus assumenda iste molestias similique earum eius, blanditiis doloribus sit ipsam in!
@endsection

@section('javascript')
    <script type="text/javascript">
        class Event extends App {
            constructor() {
                super()
            }
        }

        var event = new Event
    </script>
@endsection
