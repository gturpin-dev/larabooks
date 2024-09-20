<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Models\Comment;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CommentResource extends Resource
{
    protected static ?string $model                = Comment::class;
    protected static ?string $navigationIcon       = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationParentItem = 'Books';
    protected static ?string $navigationGroup      = 'Resources';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit( Model $record ): bool
    {
        return false;
    }

    public static function form( Form $form ): Form
    {
        return $form
            ->schema( [

            ] );
    }

    public static function table( Table $table ): Table
    {
        return $table
            ->columns( [
                Tables\Columns\TextColumn::make( 'content' )
                    ->limit( 50 ),
                Tables\Columns\TextColumn::make( 'user.name' )
                    ->label( 'From User' )
                    ->sortable(),
                Tables\Columns\TextColumn::make( 'book.title' )
                    ->label( 'On Book' )
                    ->sortable(),
                Tables\Columns\TextColumn::make( 'created_at' )
                    ->dateTime()
                    ->sortable()
                    ->toggleable( isToggledHiddenByDefault: true ),
                Tables\Columns\TextColumn::make( 'updated_at' )
                    ->dateTime()
                    ->sortable()
                    ->toggleable( isToggledHiddenByDefault: true ),
            ] )
            ->filters( [
                //
            ] )
            ->actions( [
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ] )
            ->bulkActions( [
                Tables\Actions\BulkActionGroup::make( [
                    Tables\Actions\DeleteBulkAction::make(),
                ] ),
            ] );
    }

    public static function infolist( Infolist $infolist ): Infolist
    {
        return $infolist
            ->schema( [
                TextEntry::make( 'content' ),
                TextEntry::make( 'user.name' )
                    ->label( 'From User' ),
                TextEntry::make( 'book.title' )
                    ->label( 'On Book' ),
                TextEntry::make( 'created_at' )
                    ->label( 'Created At' )
                    ->dateTime( 'd F Y' ),
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
            'index' => Pages\ListComments::route( '/' ),
            // 'create' => Pages\CreateComment::route('/create'),
            // 'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}
