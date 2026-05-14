<?php

namespace App\Filament\Resources\Formateurs;

use App\Filament\Resources\Formateurs\Pages\CreateFormateur;
use App\Filament\Resources\Formateurs\Pages\EditFormateur;
use App\Filament\Resources\Formateurs\Pages\ListFormateurs;
use App\Filament\Resources\Formateurs\Pages\ViewFormateur;
use App\Filament\Resources\Formateurs\Schemas\FormateurForm;
use App\Filament\Resources\Formateurs\Tables\FormateursTable;
use App\Models\Formateur;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FormateurResource extends Resource
{
    protected static ?string $model = Formateur::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Formateurs';

    protected static ?string $modelLabel = 'Formateur';

    protected static ?string $pluralModelLabel = 'Formateurs';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return FormateurForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FormateursTable::configure($table);
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
            'index' => ListFormateurs::route('/'),
            'create' => CreateFormateur::route('/create'),
            'view' => ViewFormateur::route('/{record}'),
            'edit' => EditFormateur::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->withCount(['formations', 'dossiers']);
    }
}
