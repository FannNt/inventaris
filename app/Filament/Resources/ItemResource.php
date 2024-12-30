<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Models\Item;
use App\Models\Ruangan;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\View\View;
use Carbon\Carbon;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('uuid')
                    ->label('id')
                    ->required()
                    ->unique(),

                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\TextInput::make('merk')
                    ->required(),

                Forms\Components\Select::make('kondisi')
                    ->options([
                        'Baik' => 'Baik',
                        'Rusak' => 'Rusak',
                    ])
                    ->required(),
//                Forms\Components\TextInput::make('keterangan')
//                    ->required(fn($get) => $get('kondisi') === 'Rusak') //next update
//                    ->visible(fn($get) => $get('kondisi') === 'Rusak'),

                TextInput::make('no_seri')
                    ->unique(),

                TextInput::make('type'),

                Forms\Components\Select::make('id_ruangan')->
                    options(function (){
                        return Ruangan::all()->pluck('name', 'id');
                })->label('Ruangan')
                ->required(),

                Forms\Components\Select::make('tahun_pengadaan')
                    ->options(
                        collect(range(now()->year, now()->year - 30))
                            ->mapWithKeys(fn($year) => [$year => $year])
                            ->toArray()
                    )
                    ->label('Tahun Pengadaan')
                    ->required(),

                Forms\Components\DatePicker::make('masa_berlaku')
                ->label('Masa Berlaku')
                ->displayFormat('d/m/Y')
                ->required(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('merk'),
                Tables\Columns\TextColumn::make('ruangan.name')->label('Ruangan'),

                Tables\Columns\TextColumn::make('kondisi'),
                Tables\Columns\TextColumn::make('masa_berlaku')
                    ->date()
                    ->color(function (Item $record): string {
                        if (!$record->masa_berlaku) {
                            return 'gray';
                        }

                        $today = now();
                        $threeMonthsFromNow = now()->addMonths(3);
                        $expiryDate = Carbon::parse($record->masa_berlaku);

                        if ($expiryDate->lt($today)) {
                            return 'danger';
                        } elseif ($expiryDate->lt($threeMonthsFromNow)) {
                            return 'warning';
                        } else {
                            return 'success';
                        }
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_ruangan')
                    ->label('Ruangan')
                    ->options(function (){
                        return Ruangan::all()->pluck('name', 'id');
                    }),
                Tables\Filters\SelectFilter::make('kondisi')
                ->label('Kondisi')
                ->options([
                    'Baik' => 'Baik',
                    'Rusak' => 'Rusak',
                ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
