namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    /**
     * Cualquier usuario autenticado puede ver la lista de mensajes.
     */
    public function viewAny(User $user): bool
    {
        return true; 
    }

    /**
     * Un usuario puede crear mensajes si está autenticado.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Solo el dueño del mensaje puede editarlo.
     */
    public function update(User $user, Message $message): bool
    {
        return $user->id === $message->user_id;
    }

    /**
     * Solo el dueño puede eliminarlo.
     */
    public function delete(User $user, Message $message): bool
    {
        return $user->id === $message->user_id;
    }
}
