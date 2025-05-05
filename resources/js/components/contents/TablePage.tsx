import { Link } from '@inertiajs/react';
import { PencilIcon, Search, Trash2 } from 'lucide-react';
import { Badge } from '../ui/badge';
import { Button } from '../ui/button';
import { Card, CardContent } from '../ui/card';
import { Input } from '../ui/input';
import { Table, TableBody, TableCell, TableHeader, TableRow } from '../ui/table';

const TablePage = () => {
    const deletePost = (id: number) => {
        alert(id);
    };
    return (
        <div className="bg-muted/40 min-h-screen justify-center gap-4">
            <div className="my-5 rounded border p-6 shadow-xl">
                <div className="mb-5 flex items-center justify-between">
                    <div className="relative w-full sm:w-1/3">
                        <Input id={'search'} className="peer ps-9" placeholder="Search..." type="search" />
                        <div className="text-muted-foreground/80 pointer-events-none absolute inset-y-0 start-0 flex items-center justify-center ps-3 peer-disabled:opacity-50">
                            <Search size={16} aria-hidden="true" />
                        </div>
                    </div>
                    <Button asChild>
                        <Link href="#" prefetch>
                            Create Product
                        </Link>
                    </Button>
                </div>
                <Card>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableCell>#</TableCell>
                                    <TableCell>Image</TableCell>
                                    <TableCell>Product Name</TableCell>
                                    <TableCell>Description</TableCell>
                                    <TableCell>Price &#8358; | &#36;</TableCell>
                                    <TableCell>Category</TableCell>
                                    <TableCell>Status</TableCell>
                                    <TableCell>Actions</TableCell>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow>
                                    <TableCell>1</TableCell>
                                    <TableCell>Image</TableCell>
                                    <TableCell>Product Name</TableCell>
                                    <TableCell>Description</TableCell>
                                    <TableCell>12000 </TableCell>
                                    <TableCell>Category</TableCell>
                                    <TableCell>
                                        <Badge className="text-xs" variant={'default'}>
                                            pending
                                        </Badge>
                                    </TableCell>
                                    <TableCell className="space-x-1">
                                        <Button asChild size={'sm'} variant={'default'}>
                                            <Link href={`#`} prefetch>
                                                <PencilIcon size={8} />
                                            </Link>
                                        </Button>
                                        <Button onClick={() => deletePost(1)} size={'sm'} variant={'destructive'}>
                                            <Trash2 size={8} className="text-white" />
                                        </Button>
                                    </TableCell>
                                </TableRow>
                                <TableRow>
                                    <TableCell>2</TableCell>
                                    <TableCell>Image2</TableCell>
                                    <TableCell>Product Name</TableCell>
                                    <TableCell>Description</TableCell>
                                    <TableCell>9000 </TableCell>
                                    <TableCell>Category</TableCell>
                                    <TableCell>
                                        <Badge className="text-xs" variant={'destructive'}>
                                            failed
                                        </Badge>
                                    </TableCell>
                                    <TableCell className="space-x-1">
                                        <Button asChild size={'sm'} variant={'default'}>
                                            <Link href={`#`} prefetch>
                                                <PencilIcon size={8} />
                                            </Link>
                                        </Button>
                                        <Button onClick={() => deletePost(2)} size={'sm'} variant={'destructive'}>
                                            <Trash2 size={8} className="text-white" />
                                        </Button>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>
                {/* <ProductsPagination products={products} /> */}
            </div>
        </div>
    );
};

export default TablePage;
