<?php

namespace App\Filament\Clusters\DataMaster\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\AssessmentSchedule;
use App\Filament\Clusters\DataMaster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use App\Filament\Clusters\DataMaster\Resources\AssessmentScheduleResource\Pages;
use App\Filament\Clusters\DataMaster\Resources\AssessmentScheduleResource\RelationManagers;

class AssessmentScheduleResource extends Resource
{
    protected static ?string $model = AssessmentSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = DataMaster::class;

    protected static ?string $navigationGroup = 'Pengaturan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('certification_scheme_id')
                    ->label('Judul skema')
                    ->relationship('certificationScheme', 'judul_skema')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('competency_unit_location_id')
                    ->label('TMU')
                    ->relationship('competencyUnitLocation', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('users')
                    ->label('Asesor')
                    ->multiple()
                    ->searchable()
                    ->columnSpanFull()
                    ->preload()
                    ->required()
                    ->relationship(titleAttribute: 'name'),
                Forms\Components\DatePicker::make('tgl_mulai')
                    ->required(),
                Forms\Components\DatePicker::make('tgl_selesai')
                    ->required(),
                Forms\Components\DatePicker::make('tgl_publish')
                    ->required(),
                Forms\Components\DatePicker::make('tgl_tutup_publish')
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktifkan Jadwal?')
                    ->default(true)
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('certificationScheme.judul_skema')
                    ->label('Skema Sertifikasi')
                    ->words(3)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('competencyUnitLocation.name')
                    ->label('TMU')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('tgl_mulai')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_selesai')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_publish')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_tutup_publish')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date()
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
            'index' => Pages\ListAssessmentSchedules::route('/'),
            'create' => Pages\CreateAssessmentSchedule::route('/create'),
            'edit' => Pages\EditAssessmentSchedule::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = static::$model;

        return (string) $modelClass::where('is_active', 1)->count();
    }

    public static function getNavigationLabel(): string
    {
        return 'Jadwal Penilaian';
    }

    public static function getModelLabel(): string
    {
        return 'Jadwal Penilaian';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Jadwal Penilaian';
    }

    public function getTitle(): string
    {
        return 'Jadwal Penilaian';
    }
}
