<?php
    
    namespace Database\Seeders;
    
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Hash;
    use App\Models\User;
    
    class UserSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            // Menghapus user yang mungkin sudah ada agar tidak duplikat
            User::query()->delete();
    
            // Membuat user admin baru
            User::create([
                'name' => 'Admin HC',
                'email' => 'admin@hc.com',
                'password' => Hash::make('PasswordnyaHC2025'), // Passwordnya adalah 'password'
            ]);
        }
    }
    

