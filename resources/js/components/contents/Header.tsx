import { Sheet, SheetContent, SheetTrigger } from '@/components/ui/sheet';
import { Link } from '@inertiajs/react';
import { Container, MenuIcon, User } from 'lucide-react';
import { Button } from '../ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '../ui/dropdown-menu';

export default function Header() {
    return (
        <div className="sticky top-0 flex items-center justify-between border bg-white px-5 py-2 dark:bg-gray-800">
            <Link href="/" className="flex items-center gap-2">
                <Container className="h-8 w-8" />
                <span className="text-lg font-semibold">Remlex Tech</span>
            </Link>

            <div className="mr-2 hidden gap-4 md:flex">
                <Link href="/" className="mt-2 font-bold">
                    Home
                </Link>
                <Link href="/table" className="mt-2 font-bold">
                    Table Page
                </Link>
                <Button className="mb-2" asChild>
                    <Link href="/login">Login</Link>
                </Button>

                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant={'outline'} size={'icon'} className="overflow-hidden rounded-full">
                            <User className="h-4 w-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuItem>
                            <Link href="/profile">Profile</Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem>
                            <Link href="/settings">Settings</Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem>
                            <Link href="/login">Login</Link>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>

            <Sheet>
                <SheetTrigger asChild>
                    <Button variant={'outline'} size={'icon'} className="lg:hidden">
                        <MenuIcon className="h-5 w-5" />
                        <span className="sr-only">Toggle Menu</span>
                    </Button>
                </SheetTrigger>
                <SheetContent side="left">
                    <div className="grid w-[200px] gap-2 px-5 py-10">
                        <img src="/images/logo.png" className="mb-5 w-64" />
                        <Link href="/dashboard" className="text-lg font-medium underline-offset-4 hover:underline">
                            Dashboard
                        </Link>
                        <Link href="/me/booking" className="text-lg font-medium underline-offset-4 hover:underline">
                            My Booking
                        </Link>
                        <Link href="/me/settings" className="text-lg font-medium underline-offset-4 hover:underline">
                            Settings
                        </Link>
                        <Link href="/faq" className="text-lg font-medium underline-offset-4 hover:underline">
                            FAQ
                        </Link>
                        <DropdownMenuSeparator />
                        <Link href="/logout" className="text-lg font-medium underline-offset-4 hover:underline">
                            Logout
                        </Link>
                    </div>
                </SheetContent>
            </Sheet>
        </div>
    );
}
