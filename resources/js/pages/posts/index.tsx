import InertiaPagination from '@/components/pagination/inertia-paginaton';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/react';
import debounce from 'lodash/debounce';
import { PencilIcon, Search, Trash2 } from 'lucide-react';
import { useEffect, useRef } from 'react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/posts',
    },
];

interface LinksType {
    url: string;
    label: string;
    active: boolean;
}

interface PostType {
    id: number;
    title: string;
    content: string;
    category: string;
    status: string;
    image: string;
}

interface PostsType {
    data: PostType[];
    links: LinksType[];
    to: number;
    from: number;
    total: number;
}
export default function AllPosts({ posts }: { posts: PostsType }) {
    const { flash } = usePage<{ flash: { message?: string } }>().props;

    console.log(posts);

    useEffect(() => {
        if (flash.message) {
            toast.success(flash.message);
        }
    }, [flash.message]);

    // Search functionality
    const handleSearch = useRef(
        debounce((query: string) => {
            router.get('/posts', { search: query }, { preserveState: true, replace: true });
        }, 500),
    ).current;

    // search method
    const onSearchChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        // const searchValue = e.target.value.toLowerCase();
        const query = e.target.value;
        handleSearch(query);
    };

    // delete post method
    const deletePost = (id: number) => {
        if (confirm('Are you sure you want to delete this post?')) {
            router.delete(`/posts/${id}`);
            toast.success('Post deleted successfully');
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Posts" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="rounded border p-6 shadow-xl">
                    <div className="mb-5 flex items-center justify-between">
                        <div className="relative w-full sm:w-1/3">
                            <Input id={'search'} className="peer ps-9" placeholder="Search..." type="search" onChange={onSearchChange} />
                            <div className="text-muted-foreground/80 pointer-events-none absolute inset-y-0 start-0 flex items-center justify-center ps-3 peer-disabled:opacity-50">
                                <Search size={16} aria-hidden="true" />
                            </div>
                        </div>
                        <Button asChild>
                            <Link href="/posts/create" prefetch>
                                Create Post
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
                                        <TableCell>Title</TableCell>
                                        <TableCell>Content</TableCell>
                                        <TableCell>Category</TableCell>
                                        <TableCell>Status</TableCell>
                                        <TableCell>Actions</TableCell>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {posts.data?.map((post, index) => (
                                        <TableRow key={post.id}>
                                            <TableCell>{index + 1}</TableCell>
                                            <TableCell>
                                                <img src={`/storage/${post.image}`} alt={post.title} className="w-10 rounded" />
                                            </TableCell>
                                            <TableCell>{post.title}</TableCell>
                                            <TableCell>{post.content.substring(0, 35)}</TableCell>
                                            <TableCell>{post.category}</TableCell>
                                            <TableCell>
                                                {post.status == '0' ? (
                                                    <Badge className="bg-red-500">Inactive</Badge>
                                                ) : (
                                                    <Badge className="bg-green-700">Active</Badge>
                                                )}
                                            </TableCell>
                                            <TableCell className="space-x-1">
                                                <Button asChild size={'sm'} variant={'default'}>
                                                    <Link href={`/posts/${post.id}/edit`} prefetch>
                                                        <PencilIcon size={8} />
                                                    </Link>
                                                </Button>
                                                <Button onClick={() => deletePost(post.id)} size={'sm'} variant={'destructive'}>
                                                    <Trash2 size={8} className="text-white" />
                                                </Button>
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        </CardContent>
                    </Card>
                    <InertiaPagination posts={posts} />
                </div>
            </div>
        </AppLayout>
    );
}
