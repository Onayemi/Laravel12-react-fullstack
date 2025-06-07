import { Button } from './ui/button';

export default function SocialiteButton({ provider }: any) {
    const handleRedirect = () => {
        window.location.href = `/auth/${provider}/redirect`;
    };
    return (
        <Button onClick={handleRedirect} className="btn btn-default mt-4 w-full">
            Login with {provider}
        </Button>
        // <Link href={`/auth/${provider}/redirect`} className="btn btn-default mt-4 w-full">
        //     Login with {provider}
        // </Link>
    );
}
