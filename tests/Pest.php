<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Applies RefreshDatabase to all Feature tests
uses(TestCase::class, RefreshDatabase::class)->in('Feature');