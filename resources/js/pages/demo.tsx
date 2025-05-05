import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Link } from '@inertiajs/react';
import { MailPlus } from 'lucide-react';

export default function Demo() {
    return (
        <div className="min-h-screen px-5">
            <Card className="mx-auto my-5 w-full max-w-lg shadow-lg">
                <CardHeader>
                    <CardTitle>Register</CardTitle>
                    <CardDescription>Enter your information to create an account?</CardDescription>
                </CardHeader>
                <CardContent>
                    <div className="grid gap-4">
                        <div className="grid grid-cols-2 gap-4">
                            <div className="grid gap-2">
                                <label htmlFor="first_name">First Name</label>
                                <Input type="text" id="first_name" placeholder="Enter First Name" />
                            </div>
                            <div className="grid gap-2">
                                <label htmlFor="last_name">Last Name</label>
                                <Input type="text" id="last_name" placeholder="Enter Last Name" />
                            </div>
                        </div>
                        <div className="grid gap-2">
                            <label htmlFor="email">Email</label>
                            <Input type="email" id="email" placeholder="hello@example.com" />
                        </div>
                        <div className="grid gap-2">
                            <label htmlFor="role">Role</label>
                            <Select>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select a role" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectLabel>User</SelectLabel>
                                        <SelectItem value="user">User</SelectItem>
                                        <SelectItem value="admin">Admin</SelectItem>
                                        <SelectItem value="employee">Employee</SelectItem>
                                        <SelectItem value="superadmin">SuperAdmin</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>
                        <Button type="submit" className="w-full">
                            Register <MailPlus className="ml-2 h-4 w-4" />
                        </Button>
                    </div>
                    <div className="mt-4 text-center text-sm">
                        Already Have an account?{' '}
                        <Link href="/login" className="text-primary">
                            Login
                        </Link>
                    </div>
                </CardContent>
            </Card>
        </div>
    );
}
