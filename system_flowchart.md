# Flowchart Sistem DBHC (Dashboard Kepegawaian Regional 1 PT Angkasa Pura Indonesia)

## 1. Alur Utama Sistem

```mermaid
flowchart TD
    A[User Mengakses Aplikasi] --> B{Sudah Login?}
    B -->|Tidak| C[Halaman Login]
    B -->|Ya| D[Dashboard Utama]
    
    C --> E[Input Username & Password]
    E --> F{Valid?}
    F -->|Tidak| G[Error Message]
    G --> C
    F -->|Ya| H{Role User?}
    
    H -->|Admin| I[Admin Dashboard]
    H -->|User Biasa| D
    
    D --> J[Menu Utama]
    I --> K[Admin Menu]
```

## 2. Struktur Menu dan Hak Akses

```mermaid
flowchart TD
    A[Dashboard Utama] --> B[Analytics]
    A --> C[Data Karyawan]
    A --> D[Formasi]
    A --> E[Profile]
    A --> F[Version Control]
    
    B --> B1[Analitik Organik]
    B --> B2[Analitik Outsourcing]
    
    C --> C1{User Role?}
    C1 -->|Admin| C2[Full Access]
    C1 -->|User| C3[View Only]
    
    C2 --> C4[Create/Edit/Delete]
    C2 --> C5[Import Data]
    C2 --> C6[Export Data]
    C3 --> C7[View Data]
    C3 --> C6
    
    D --> D1{User Role?}
    D1 -->|Admin| D2[Full Access]
    D1 -->|User| D3[View Only]
    
    D2 --> D4[Create/Edit/Delete]
    D2 --> D5[Import Formasi]
    D2 --> D6[Export Formasi]
    D3 --> D7[View Formasi]
    D3 --> D6
```

## 3. Alur Detail Dashboard

```mermaid
flowchart TD
    A[Dashboard Index] --> B[Load KPI Data]
    B --> C[Total Karyawan]
    B --> D[Gender Distribution]
    B --> E[Age Distribution]
    B --> F[Education Level]
    B --> G[Jabatan Lowong]
    
    G --> H[Click Detail Jabatan]
    H --> I{User Role?}
    I -->|Admin| J[View + Export Detail]
    I -->|User| K[View Detail Only]
    
    C --> L[Generate Charts]
    D --> L
    E --> L
    F --> L
    
    L --> M[Interactive Charts dengan ECharts]
    M --> N[Click Chart Elements]
    N --> O[Navigate to Analytics]
```

## 4. Alur Data Karyawan Management

```mermaid
flowchart TD
    A[Data Karyawan Menu] --> B{User Role?}
    B -->|User| C[View Mode]
    B -->|Admin| D[Admin Mode]
    
    C --> E[List Karyawan]
    C --> F[Export Data]
    C --> G[Download Template]
    
    D --> H[Full CRUD Access]
    H --> I[Create New]
    H --> J[Edit Existing]
    H --> K[Delete Record]
    H --> L[Import Operations]
    H --> F
    H --> G
    
    L --> M[Import Add Mode]
    L --> N[Import Replace Mode]
    
    M --> O[Validate Data]
    N --> O
    O --> P{Valid?}
    P -->|Ya| Q[Process Import]
    P -->|Tidak| R[Show Errors]
    R --> L
    Q --> S[Success Message]
```

## 5. Alur Formasi Management

```mermaid
flowchart TD
    A[Formasi Menu] --> B{User Role?}
    B -->|User| C[View Mode]
    B -->|Admin| D[Admin Mode]
    
    C --> E[List Formasi]
    C --> F[Export Data]
    C --> G[Download Template]
    
    D --> H[Full CRUD Access]
    H --> I[Create New Formasi]
    H --> J[Edit Formasi]
    H --> K[Delete Formasi]
    H --> L[Import Operations]
    H --> F
    H --> G
    
    L --> M[Import Add Mode]
    L --> N[Import Replace Mode]
    
    M --> O[Validate Data]
    N --> O
    O --> P{Valid?}
    P -->|Ya| Q[Process Import]
    P -->|Tidak| R[Show Errors]
    R --> L
    Q --> S[Update Jabatan Lowong]
    S --> T[Refresh Dashboard]
```

## 6. Alur Analytics System

```mermaid
flowchart TD
    A[Analytics Menu] --> B[Analitik Organik]
    A --> C[Analitik Outsourcing]
    
    B --> D[Load Organic Data]
    D --> E[Piramida Jabatan per Lokasi]
    D --> F[Gender Distribution per Lokasi]
    D --> G[Age Groups per Lokasi]
    D --> H[Tenure Analysis]
    D --> I[Education Analysis]
    
    E --> J[ECharts Visualization]
    F --> J
    G --> J
    H --> J
    I --> J
    
    J --> K[Interactive Tabs]
    K --> L[Analisis 1 - Demographics]
    K --> M[Analisis 2 - Education]
    K --> N[Analisis 3 - Unit Analysis]
    
    C --> O[Load Outsourcing Data]
    O --> P[Similar Analytics for Outsourcing]
```

## 7. Alur Version Control

```mermaid
flowchart TD
    A[Version Control] --> B[List All Versions]
    B --> C[Create New Version]
    B --> D[Restore Version]
    B --> E[Download Version]
    B --> F[Delete Version]
    
    C --> G[Snapshot Current Data]
    G --> H[Save Version]
    
    D --> I{Confirm Restore?}
    I -->|Ya| J[Restore Data]
    I -->|Tidak| B
    J --> K[Update Database]
    K --> L[Refresh System]
    
    E --> M[Generate Export File]
    F --> N{Confirm Delete?}
    N -->|Ya| O[Delete Version]
    N -->|Tidak| B
```

## 8. Alur Profile Management

```mermaid
flowchart TD
    A[Profile Menu] --> B[View Profile]
    B --> C[Edit Profile Info]
    B --> D[Change Password]
    
    C --> E[Update Name/Email]
    E --> F[Validate Input]
    F --> G{Valid?}
    G -->|Ya| H[Save Changes]
    G -->|Tidak| I[Show Errors]
    I --> C
    H --> J[Success Message]
    
    D --> K[Current Password]
    K --> L[New Password]
    L --> M[Confirm Password]
    M --> N[Validate Passwords]
    N --> O{Valid?}
    O -->|Ya| P[Update Password]
    O -->|Tidak| Q[Show Errors]
    Q --> D
    P --> R[Success Message]
```

## 9. Security & Authentication Flow

```mermaid
flowchart TD
    A[Every Request] --> B[Check Authentication]
    B --> C{Logged In?}
    C -->|Tidak| D[Redirect to Login]
    C -->|Ya| E[Check Route Permissions]
    
    E --> F{Admin Route?}
    F -->|Ya| G{User is Admin?}
    F -->|Tidak| H[Allow Access]
    
    G -->|Ya| H
    G -->|Tidak| I[Access Denied]
    
    H --> J[Process Request]
    J --> K[Return Response]
    
    D --> L[Login Form]
    L --> M[Submit Credentials]
    M --> N[Validate User]
    N --> O{Valid?}
    O -->|Ya| P[Create Session]
    O -->|Tidak| Q[Login Error]
    P --> R[Redirect to Dashboard]
    Q --> L
```

## 10. Data Flow Architecture

```mermaid
flowchart LR
    A[User Interface] --> B[Routes/Controllers]
    B --> C[Middleware]
    C --> D[Business Logic]
    D --> E[Models/Database]
    
    E --> F[Data Processing]
    F --> G[Chart Generation]
    G --> H[ECharts Visualization]
    
    E --> I[Export Functions]
    I --> J[Excel/CSV Files]
    
    E --> K[Import Functions]
    K --> L[Data Validation]
    L --> M[Database Updates]
    
    subgraph "Frontend"
        A
        H
    end
    
    subgraph "Backend"
        B
        C
        D
        E
        F
        G
        I
        J
        K
        L
        M
    end
```

## Penjelasan Sistem:

### **Karakteristik Utama:**
1. **Role-based Access Control**: Admin vs User biasa
2. **Dashboard Interaktif**: Charts dengan ECharts
3. **Data Management**: CRUD operations untuk Karyawan & Formasi  
4. **Analytics**: Detailed analysis untuk Organik vs Outsourcing
5. **Version Control**: Snapshot dan restore data
6. **Import/Export**: Excel/CSV processing
7. **Security**: Authentication & authorization layers

### **User Roles:**
- **Admin**: Full access - CRUD, Import, Export, Delete
- **User**: Read access - View, Export only

### **Core Features:**
- Dashboard dengan KPI dan charts interaktif
- Management data karyawan dan formasi
- Analytics mendalam (organik vs outsourcing)
- Version control untuk data backup/restore
- Profile management
- Secure authentication system