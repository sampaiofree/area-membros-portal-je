@extends('layouts.app')

@section('title', $lesson->title)

@section('content')
    <livewire:student.lesson-screen :course-id="$course->id" :lesson-id="$lesson->id" />
@endsection
