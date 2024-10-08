<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthorResource\Pages;
use App\Models\Author;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuthorResource extends Resource
{
    protected static ?string $model                = Author::class;
    protected static ?string $navigationIcon       = 'heroicon-o-user';
    protected static ?string $navigationParentItem = 'Books';
    protected static ?string $navigationGroup      = 'Resources';

    public static function form( Form $form ): Form
    {
        return $form
            ->schema( [
                TextInput::make( 'name' )
                    ->maxLength( 255 )
                    ->required(),
                Textarea::make( 'biography' )
                    ->rows( 5 )
                    ->required(),
                DatePicker::make( 'birthdate' )
                    ->format( 'Y-m-d' )
                    ->required(),
                TextInput::make( 'nationality' )
                    ->maxLength( 255 )
                    ->required(),
            ] );
    }

    public static function table( Table $table ): Table
    {
        return $table
            ->columns( [
                TextColumn::make( 'name' )
                    ->description( fn ( Author $author ) => str( $author->biography )->limit( 75 ) )
                    ->searchable()
                    ->sortable(),
                TextColumn::make( 'nationality' )
                    ->sortable(),
                TextColumn::make( 'birthdate' )
                    ->date( 'd F Y' )
                    ->sortable(),
            ] )
            ->filters( [
                //
            ] )
            ->actions( [
                Tables\Actions\EditAction::make(),
            ] )
            ->bulkActions( [
                Tables\Actions\BulkActionGroup::make( [
                    Tables\Actions\DeleteBulkAction::make(),
                ] ),
            ] );
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
            'index'  => Pages\ListAuthors::route( '/' ),
            'create' => Pages\CreateAuthor::route( '/create' ),
            'edit'   => Pages\EditAuthor::route( '/{record}/edit' ),
        ];
    }
}
