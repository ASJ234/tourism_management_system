@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Tour Package</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('tour_operator.packages.update', $package) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Package Name</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $package->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="destination_id" class="form-label">Destination</label>
                            <select class="form-select @error('destination_id') is-invalid @enderror" 
                                    id="destination_id" 
                                    name="destination_id" 
                                    required>
                                <option value="">Select a destination</option>
                                @foreach($destinations as $destination)
                                    <option value="{{ $destination->destination_id }}" 
                                            {{ old('destination_id', $package->destination_id) == $destination->destination_id ? 'selected' : '' }}>
                                        {{ $destination->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('destination_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      required>{{ old('description', $package->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price ($)</label>
                                <input type="number" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price', $package->price) }}" 
                                       step="0.01" 
                                       min="0" 
                                       required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="duration_days" class="form-label">Duration (Days)</label>
                                <input type="number" 
                                       class="form-control @error('duration_days') is-invalid @enderror" 
                                       id="duration_days" 
                                       name="duration_days" 
                                       value="{{ old('duration_days', $package->duration_days) }}" 
                                       min="1" 
                                       required>
                                @error('duration_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="max_capacity" class="form-label">Maximum Capacity</label>
                                <input type="number" 
                                       class="form-control @error('max_capacity') is-invalid @enderror" 
                                       id="max_capacity" 
                                       name="max_capacity" 
                                       value="{{ old('max_capacity', $package->max_capacity) }}" 
                                       min="1" 
                                       required>
                                @error('max_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="total_available_slots" class="form-label">Available Slots</label>
                                <input type="number" 
                                       class="form-control @error('total_available_slots') is-invalid @enderror" 
                                       id="total_available_slots" 
                                       name="total_available_slots" 
                                       value="{{ old('total_available_slots', $package->total_available_slots) }}" 
                                       min="1" 
                                       required>
                                @error('total_available_slots')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="difficulty_level" class="form-label">Difficulty Level</label>
                                <select class="form-select @error('difficulty_level') is-invalid @enderror" 
                                        id="difficulty_level" 
                                        name="difficulty_level" 
                                        required>
                                    <option value="">Select difficulty level</option>
                                    <option value="Easy" {{ old('difficulty_level', $package->difficulty_level) == 'Easy' ? 'selected' : '' }}>Easy</option>
                                    <option value="Moderate" {{ old('difficulty_level', $package->difficulty_level) == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                                    <option value="Challenging" {{ old('difficulty_level', $package->difficulty_level) == 'Challenging' ? 'selected' : '' }}>Challenging</option>
                                </select>
                                @error('difficulty_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="{{ old('start_date', $package->start_date->format('Y-m-d')) }}" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" 
                                   class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date', $package->end_date->format('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d', strtotime('+2 days')) }}" 
                                   required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input @error('is_active') is-invalid @enderror" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $package->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active Package</label>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('tour_operator.packages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Packages
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Package
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 