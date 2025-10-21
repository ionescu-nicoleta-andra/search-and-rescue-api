<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use UnitEnum;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedUser;

    protected static UnitEnum|string|null $navigationGroup = 'User Management';

    // ---- Form ----
    public static function form(Forms\Form|\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Full Name')
                    ->required(),

                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required(),

                Toggle::make('approved')
                    ->label('Approved')
                    ->helperText('Only approved users can log in.')
                    ->default(false),

                Select::make('roles')
                    ->label('Roles')
                    ->multiple()
                    ->relationship('roles', 'name') // connects to Spatie Role
                    ->preload(),

                TextInput::make('password')
                    ->label('Password')
                    ->password()                 // hides input
                    ->required(fn ($record) => ! $record) // required only on create
                    ->minLength(8)
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->dehydrated(false) // don’t save to DB
                    ->same('password') // validation must match password
            ]);
    }

    // ---- Table ----
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                IconColumn::make('approved')
                    ->boolean()
                    ->label('Approved')
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->separator(', ')
                    ->sortable(),


            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),

                BulkAction::make('approve')
                    ->label('Approve Users')
                    ->action(function (Collection $records) {
                        foreach ($records as $user) {
                            $user->approved = true;
                            $user->save();
                        }
                    })
                    ->requiresConfirmation() // optional, show a "Are you sure?" dialog
                    ->color('success') // nice green button
                    ->icon('heroicon-o-check-circle'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
