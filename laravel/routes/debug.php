<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\User;

// Database debug route - remove after testing
Route::get('/debug-auth', function () {
    try {
        echo "<h2>Database Connection Test</h2>";
        DB::connection()->getPdo();
        echo "✅ Database connection successful<br><br>";
        
        echo "<h2>Users Table Structure</h2>";
        $columns = DB::select("DESCRIBE users");
        echo "<table border='1'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>{$column->Field}</td>";
            echo "<td>{$column->Type}</td>";
            echo "<td>{$column->Null}</td>";
            echo "<td>{$column->Key}</td>";
            echo "<td>{$column->Default}</td>";
            echo "<td>{$column->Extra}</td>";
            echo "</tr>";
        }
        echo "</table><br>";
        
        echo "<h2>Users in Database</h2>";
        $users = User::all();
        if ($users->count() > 0) {
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>Created</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>{$user->id}</td>";
                echo "<td>{$user->name}</td>";
                echo "<td>" . ($user->username ?? 'NULL') . "</td>";
                echo "<td>{$user->email}</td>";
                echo "<td>{$user->created_at}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "❌ No users found in database<br>";
        }
        
        echo "<br><h2>Test Admin Login</h2>";
        $admin = User::where('username', 'admin123')->first();
        if ($admin) {
            echo "✅ Admin user found: " . $admin->name . "<br>";
            echo "Username: " . $admin->username . "<br>";
            echo "Email: " . $admin->email . "<br>";
        } else {
            echo "❌ Admin user not found<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ Database error: " . $e->getMessage();
    }
});