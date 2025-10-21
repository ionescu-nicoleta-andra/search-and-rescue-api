<?php

namespace App\Filament\Resources\Permissions;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Spatie\Permission\Models\Permission;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;

use App\Filament\Resources\Permissions\Pages;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static string|null|\UnitEnum $navigationGroup = 'User Management';

    protected static ?string $navigationLabel = 'Permissions';

    protected static ?string $pluralLabel = 'Permissions';

    public static function form(Forms\Form|\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Permission Name')
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('guard_name')->sortable()->label('Guard'),
                TextColumn::make('created_at')->dateTime('Y-m-d H:i')->label('Created'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit'   => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
