<?php

namespace App\Filament\Clusters\DataMaster\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use App\Models\CertificationScheme;
use App\Filament\Clusters\DataMaster;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use App\Filament\Clusters\DataMaster\Resources\CertificationSchemeResource\Pages;
use App\Filament\Clusters\DataMaster\Resources\CertificationSchemeResource\RelationManagers;

class CertificationSchemeResource extends Resource
{
    protected static ?string $model = CertificationScheme::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = DataMaster::class;

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $recordTitleAttribute = 'nomor_skema';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Skema Sertifikasi')
                    ->description('Isi data skema sertifikasi sesuai informasi resmi')
                    ->schema([
                        Forms\Components\TextInput::make('nomor_skema')
                            ->required()
                            ->maxLength(255)
                            ->placeholder(function () {
                                return 'Contoh: 01/S/LSPMM/' . Carbon::now()->format('Y');
                            })
                            ->unique(CertificationScheme::class, 'nomor_skema', ignoreRecord: true),
                        Forms\Components\TextInput::make('judul_skema')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Junior Web Developer'),
                        Forms\Components\Select::make('jenis_skema')
                            ->required()
                            ->options([
                                'KKNI' => 'KKNI',
                                'Okupasi' => 'Okupasi',
                                'Klaster' => 'Klaster',
                            ])
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('deskripsi_skema')
                            ->required()
                            ->placeholder('Tuliskan deskripsi lengkap tentang skema...')
                            ->columnSpanFull()
                            ->rows(5)
                            ->cols(5),
                        Forms\Components\Select::make('tujuan_skema')
                            ->options([
                                'Sertifikasi' => 'Sertifikasi',
                                'Pengakuan Kompetensi Terkini (PKT)' => 'Pengakuan Kompetensi Terkini (PKT)',
                                'Rekognisi Pembelajaran Lampau (RPL)' => 'Rekognisi Pembelajaran Lampau (RPL)',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('kode_referensi')
                            ->label('Kode Referensi / Kategori')
                            ->placeholder('Contoh: ICT/WebDev')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tahun_terbit')
                            ->required()
                            ->placeholder(function () {
                                return 'Contoh: ' . Carbon::now()->format('Y');
                            })
                            ->maxLength(255),
                        Forms\Components\TextInput::make('lembaga_penyelenggara')
                            ->required()
                            ->placeholder('Contoh: LSP Maju Mandiri')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('harga')
                            ->required()
                            ->placeholder('Contoh: 100.0000')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->prefix('Rp ')
                            ->numeric(),
                        Forms\Components\Toggle::make('is_active_skema')
                            ->label('Aktifkan Skema?')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\Select::make('document_type_id')
                            ->label('Tipe Dokumen')
                            ->required()
                            ->columnSpanFull()
                            ->preload()
                            ->multiple()
                            ->relationship(name: 'documentTypes', titleAttribute: 'name'),
                    ])
                    ->columns(2),
                Section::make()
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('units')
                            ->relationship()
                            ->label('Unit')
                            ->collapsible()
                            ->schema([
                                Forms\Components\TextInput::make('nomor')
                                    ->label('Nomor Unit')
                                    ->required()
                                    ->placeholder('Contoh: N.821100.046.01')
                                    ->maxLength(255)
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Unit')
                                    ->placeholder('Contoh: Unit A')
                                    ->columnSpan(1),

                                \Filament\Forms\Components\Repeater::make('elements')
                                    ->relationship()
                                    ->label('Elemen')
                                    ->collapsible()
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Elemen')
                                            ->placeholder('Contoh: Membuat halaman login')
                                            ->columnSpanFull(),

                                        Forms\Components\RichEditor::make('content')
                                            ->placeholder('Contoh: Validasi, UI sesuai desain')
                                            ->columnSpanFull(),
                                    ])
                                    ->reorderable()
                                    ->columnSpan(2)
                                    ->defaultItems(1)
                                    ->addActionLabel('Tambah Elemen'),
                            ])
                            ->columns(2) // 🟢 Ini penting untuk membagi dua kolom
                            ->reorderable()
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Unit')
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_skema')
                    ->searchable(),
                Tables\Columns\TextColumn::make('judul_skema')
                    ->words(3)
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_skema')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tujuan_skema')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('kode_referensi')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun_terbit')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('lembaga_penyelenggara')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active_skema')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->label('Dibuat')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                DateRangeFilter::make('created_at'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCertificationSchemes::route('/'),
            'create' => Pages\CreateCertificationScheme::route('/create'),
            'edit' => Pages\EditCertificationScheme::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Skema Sertifikasi';
    }

    public static function getModelLabel(): string
    {
        return 'Skema Sertifikasi';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Skema Sertifikasi';
    }

    public function getTitle(): string
    {
        return 'Skema Sertifikasi';
    }
}
