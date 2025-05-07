import ProductsPagination from '@/components/pagination/products-paginaton';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { PencilIcon, Search, Trash2 } from 'lucide-react';
import { useEffect } from 'react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Manage Products',
        href: '/products',
    },
];

interface LinksType {
    url: string;
    label: string;
    active: boolean;
}

interface ProductType {
    id: number;
    prod_name: string;
    description: string;
    price: number;
    category: string;
    status: string;
    image: string;
}

interface ProductsType {
    data: ProductType[];
    links: LinksType[];
    to: number;
    from: number;
    total: number;
}

const formatCurrency = (number: number, symbol = '₦') => {
    // Add thousands separator
    const formattedNumber = number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    // Format the number as a currency string
    return `${symbol} ${formattedNumber}`;
};

export default function Products({ products }: { products: ProductsType }) {
    const { flash } = usePage<{ flash: { message?: string } }>().props;

    useEffect(() => {
        if (flash.message) {
            toast.success(flash.message);
        }
    }, [flash.message]);

    const deletePost = (id: number) => {
        alert(id);
    };
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Products" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="rounded border p-6 shadow-xl">
                    <div className="mb-5 flex items-center justify-between">
                        <div className="relative w-full sm:w-1/3">
                            <Input id={'search'} className="peer ps-9" placeholder="Search..." type="search" />
                            <div className="text-muted-foreground/80 pointer-events-none absolute inset-y-0 start-0 flex items-center justify-center ps-3 peer-disabled:opacity-50">
                                <Search size={16} aria-hidden="true" />
                            </div>
                        </div>
                        <Button asChild>
                            <Link href="/products/create" prefetch>
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
                                        <TableCell>{formatCurrency(12000)} </TableCell>
                                        <TableCell>Category</TableCell>
                                        <TableCell>Status</TableCell>
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
                                </TableBody>
                            </Table>
                        </CardContent>
                    </Card>
                    <ProductsPagination products={products} />
                </div>
            </div>
        </AppLayout>
    );
}
